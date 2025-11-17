// admin.js (seller/admin) — improved and robust
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.4.0/firebase-app.js";
import {
  getDatabase, ref, onChildAdded, push, serverTimestamp, off
} from "https://www.gstatic.com/firebasejs/10.4.0/firebase-database.js";
import {
  getAuth, GoogleAuthProvider, signInWithPopup, onAuthStateChanged, signOut
} from "https://www.gstatic.com/firebasejs/10.4.0/firebase-auth.js";

/* ====== FIREBASE CONFIG (keep databaseURL correct) ====== */
const firebaseConfig = {
  apiKey: "AIzaSyBTMgrqBiYkpD9_pGbK29leTqLkB5w3c_8",
  authDomain: "chatbox-b9179.firebaseapp.com",
  databaseURL: "https://chatbox-b9179-default-rtdb.firebaseio.com",
  projectId: "chatbox-b9179",
  storageBucket: "chatbox-b9179.appspot.com",
  messagingSenderId: "297517994036",
  appId: "1:297517994036:web:a8d9103e3f426b15e9ba61",
  measurementId: "G-3B8QRQ1KGC"
};
/* ======================================================= */

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);
const auth = getAuth(app);
const provider = new GoogleAuthProvider();

/* --- safe DOM lookups --- */
function $(id) { return document.getElementById(id); }

const messagesDiv = $('messages');
const shopIdInput = $('shopId');
const replyInput = $('reply');
const loadBtn = $('loadBtn');
const sendBtn = $('sendBtn');
const authArea = $('authArea');

/* If elements are missing, show a console warning (prevents runtime crashes) */
if (!messagesDiv || !shopIdInput || !replyInput || !loadBtn || !sendBtn) {
  console.warn('admin.js: one or more expected DOM elements are missing. Check admin.html IDs.');
}

/* Simple status banner for quick feedback */
function ensureBanner() {
  let b = document.getElementById('__admin_status');
  if (!b) {
    b = document.createElement('div');
    b.id = '__admin_status';
    b.style.position = 'fixed';
    b.style.right = '16px';
    b.style.bottom = '16px';
    b.style.zIndex = 99999;
    b.style.background = 'rgba(0,0,0,0.6)';
    b.style.color = '#fff';
    b.style.padding = '8px 12px';
    b.style.borderRadius = '8px';
    b.style.fontSize = '13px';
    document.body.appendChild(b);
  }
  return b;
}
const statusBanner = ensureBanner();
function setStatus(text, warn = false) {
  statusBanner.textContent = text;
  statusBanner.style.background = warn ? 'rgba(183,28,28,0.9)' : 'rgba(0,0,0,0.6)';
}

/* utility: escape HTML for safety */
function escapeHtml(str='') {
  return String(str).replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'", '&#39;');
}

/* track DB listener so we can detach cleanly */
let chatRef = null;
let childListener = null;

/* update auth area UI (if present) */
onAuthStateChanged(auth, (user) => {
  if (authArea) {
    if (user) {
      authArea.innerHTML = `Signed in as <strong>${user.displayName || user.email || 'Admin'}</strong>
        <button id="signOutBtn" style="margin-left:8px">Sign out</button>`;
      const btn = $('signOutBtn');
      if (btn) btn.onclick = () => signOut(auth).catch(e => console.error('Sign-out failed', e));
      setStatus('Admin signed in', false);
    } else {
      authArea.innerHTML = `<button id="signInBtn">Sign in with Google</button>`;
      const btn = $('signInBtn');
      if (btn) btn.onclick = () => signInWithPopup(auth, provider).catch(err => {
        console.error('Sign-in failed', err);
        alert('Sign-in failed. See console.');
      });
      setStatus('Not signed in (admin)', true);
    }
  }
});

/* Load messages for a shop - attaches a child_added listener */
loadBtn?.addEventListener('click', () => {
  const shop = (shopIdInput?.value || '').trim();
  if (!shop) return alert('Enter Shop ID');

  // detach previous listener
  if (chatRef && childListener) {
    try {
      off(chatRef, 'child_added', childListener);
    } catch (e) {
      // if off fails, clear anyway
    }
    childListener = null;
    messagesDiv && (messagesDiv.innerHTML = '');
  }

  const path = `chats/${shop}/messages`;
  chatRef = ref(db, path);

  messagesDiv && (messagesDiv.innerHTML = '<p>Loading messages…</p>');
  setStatus('Loading messages…');

  // define the callback so we can detach it later
  childListener = (snap) => {
    const m = snap.val();
    const el = document.createElement('div');
    el.style.margin = '10px 0';
    el.style.padding = '10px';
    el.style.borderRadius = '8px';
    el.style.background = m.role === 'client' ? '#e6fffb' : '#f1f6f9';
    const ts = m.ts && typeof m.ts === 'number' ? new Date(m.ts) : new Date();
    el.innerHTML = `<div style="font-size:13px;color:#333"><strong>${escapeHtml(m.name || 'Guest')}</strong>
                    <small style="color:#666;margin-left:8px">${ts.toLocaleString()}</small></div>
                    <div style="margin-top:6px;white-space:pre-wrap">${escapeHtml(m.text || '')}</div>`;
    messagesDiv && messagesDiv.appendChild(el);
    if (messagesDiv) messagesDiv.scrollTop = messagesDiv.scrollHeight;
    setStatus('Messages loaded', false);
  };

  try {
    onChildAdded(chatRef, childListener, (err) => {
      console.error('Load messages error', err);
      messagesDiv && (messagesDiv.innerHTML = '<p style="color:red">Failed to load messages. See console.</p>');
      setStatus('Failed to load messages', true);
    });
  } catch (err) {
    console.error('onChildAdded threw', err);
    setStatus('Failed to attach DB listener', true);
  }
});

/* Send reply */
sendBtn?.addEventListener('click', async () => {
  const text = (replyInput?.value || '').trim();
  if (!text) return alert('Enter reply text');
  if (!chatRef) return alert('Load a Shop ID first');

  // Ensure admin signed-in
  if (!auth.currentUser) {
    const proceed = confirm('You are not signed in. Sign in with Google as admin?');
    if (!proceed) return;
    try {
      await signInWithPopup(auth, provider);
    } catch (err) {
      console.error('Sign-in failed', err);
      return alert('Sign-in failed. See console.');
    }
  }

  try {
    await push(chatRef, {
      name: auth.currentUser?.displayName || 'Seller',
      role: 'seller',
      text,
      ts: serverTimestamp()
    });
    replyInput.value = '';
    setStatus('Reply sent', false);
  } catch (err) {
    console.error('Send failed', err);
    alert('Failed to send reply. See console.');
    setStatus('Send failed', true);
  }
});
