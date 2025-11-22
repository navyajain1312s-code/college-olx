// chat.js (ES module - buyer widget) — debugged + robust
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.4.0/firebase-app.js";
import {
  getDatabase, ref, push, onChildAdded, off, query, limitToLast, serverTimestamp, get
} from "https://www.gstatic.com/firebasejs/10.4.0/firebase-database.js";
import {
  getAuth, signInAnonymously, onAuthStateChanged
} from "https://www.gstatic.com/firebasejs/10.4.0/firebase-auth.js";

/* ====== FIREBASE CONFIG - ensure databaseURL is correct ====== */
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
/* ====================================================================== */

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);
const auth = getAuth(app);

/* ------- Small on-page status banner so you don't always need DevTools ------- */
function ensureStatusBanner() {
  let banner = document.getElementById('__chat_status_banner');
  if (!banner) {
    banner = document.createElement('div');
    banner.id = '__chat_status_banner';
    banner.style.position = 'fixed';
    banner.style.left = '16px';
    banner.style.bottom = '16px';
    banner.style.zIndex = 99999;
    banner.style.background = 'rgba(0,0,0,0.6)';
    banner.style.color = '#fff';
    banner.style.padding = '8px 12px';
    banner.style.borderRadius = '8px';
    banner.style.fontSize = '13px';
    banner.style.maxWidth = 'calc(100% - 40px)';
    document.body.appendChild(banner);
  }
  return banner;
}
const statusBanner = ensureStatusBanner();
function setStatus(text, warning = false) {
  statusBanner.textContent = text;
  statusBanner.style.background = warning ? 'rgba(183,28,28,0.92)' : 'rgba(0,0,0,0.6)';
}

/* Detailed sign-in with helpful logs */
async function trySignInAnonymous(retries = 2) {
  try {
    const res = await signInAnonymously(auth);
    console.log('Anonymous sign-in success:', res);
    setStatus('Connected (anonymous)', false);
    return true;
  } catch (err) {
    // Log everything meaningful
    console.error('Anonymous sign-in failed (error object):', err);
    try {
      console.error('err.code:', err.code, ' err.message:', err.message);
      if (err.customData) console.error('err.customData:', err.customData);
    } catch (e) { /* ignore */ }

    // If the error came from the REST API (identitytoolkit) it may have a network response
    // Check the browser's Network tab for a request to identitytoolkit and copy the Response JSON.
    setStatus('Anonymous auth failed — check console', true);

    if (retries > 0) {
      console.log('Retry anonymous sign-in, attempts left:', retries);
      return trySignInAnonymous(retries - 1);
    }
    return false;
  }
}

/* Build initial DOM widget */
const ROOT = document.getElementById('chat-root');
if (!ROOT) {
  console.error('No #chat-root found in HTML.');
} else {
  ROOT.innerHTML = `
    <button class="chat-open-btn" id="openBtn" aria-label="Open chat">Chat</button>
    <div id="widget" class="chat-container" style="display:none" role="region" aria-label="Chat widget">
      <div class="chat-header">
        <div>Chat with seller</div>
        <div>
          <button id="minBtn" title="Minimize" style="background:transparent;border:0;color:#fff;cursor:pointer">—</button>
          <button id="closeBtn" title="Close" style="background:transparent;border:0;color:#fff;cursor:pointer">✕</button>
        </div>
      </div>
      <div class="chat-body">
        <div id="messages" class="messages" role="log" aria-live="polite"></div>
      </div>
      <form id="form" class="chat-form" autocomplete="off">
        <input id="name" class="name" type="text" placeholder="Your name (optional)" maxlength="50" />
        <input id="msg" class="message" type="text" placeholder="Type a message..." maxlength="1000" />
        <button type="submit">Send</button>
      </form>
    </div>
  `;
}

/* UI refs */
const btnOpen = document.getElementById('openBtn');
const widget = document.getElementById('widget');
const btnClose = document.getElementById('closeBtn');
const btnMin = document.getElementById('minBtn');
const messagesEl = document.getElementById('messages');
const form = document.getElementById('form');
const inputMsg = document.getElementById('msg');
const inputName = document.getElementById('name');

if (btnOpen) btnOpen.addEventListener('click', () => { widget.style.display = 'flex'; btnOpen.style.display = 'none'; setTimeout(()=> inputMsg.focus(), 120); });
if (btnClose) btnClose.addEventListener('click', () => { widget.style.display = 'none'; if(btnOpen) btnOpen.style.display = 'inline-block'; });
if (btnMin) btnMin.addEventListener('click', () => { widget.style.display = 'none'; if(btnOpen) btnOpen.style.display = 'inline-block'; });

/* Chat DB wiring */
const SHOP_ID = window.SHOP_ID || 'shop-123';

const CHAT_PATH = `chats/${SHOP_ID}/messages`;
let chatRef = null;
let childListener = null;
let latestQuery = null;

function escapeHtml(s = '') {
  return s
    .replaceAll('&','&amp;')
    .replaceAll('<','&lt;')
    .replaceAll('>','&gt;')
    .replaceAll('"','&quot;')
    .replaceAll("'",'&#39;');
}

function renderMessage(m){
  if (!m) return;
  const wrapper = document.createElement('div');
  wrapper.className = 'msg ' + (m.role === 'seller' ? 'seller' : 'client');

  const meta = document.createElement('div');
  meta.className = 'meta';
  let ts = m.ts;
  if (ts && typeof ts === 'object') ts = Date.now();
  if (!ts) ts = Date.now();
  meta.textContent = `${m.name || (m.role==='seller' ? 'Seller' : 'You')} • ${new Date(ts).toLocaleString()}`;

  const body = document.createElement('div');
  body.className = 'body';
  body.innerHTML = escapeHtml(m.text || '');

  wrapper.appendChild(meta);
  wrapper.appendChild(body);

  messagesEl.appendChild(wrapper);
  messagesEl.scrollTop = messagesEl.scrollHeight;
}

/* Probe DB read permission once (using get) — returns true if readable */
async function probeDbRead() {
  try {
    chatRef = ref(db, CHAT_PATH);
    const snap = await get(chatRef);
    // if snapshot exists or empty, read succeeded
    console.log('DB probe: read ok. snapshot exists:', snap.exists());
    setStatus('DB accessible', false);
    return true;
  } catch (err) {
    console.error('DB probe read failed:', err);
    setStatus('DB read blocked: permission_denied or rules', true);
    return false;
  }
}

/* Attach DB listeners only after auth + probe */
async function attachListenersSafely() {
  if (!chatRef) chatRef = ref(db, CHAT_PATH);
  latestQuery = query(chatRef, limitToLast(200));
  if (childListener) return; // already attached

  // probe read permission
  const ok = await probeDbRead();
  if (!ok) {
    console.warn('attachListenersSafely: DB read probe failed — not attaching listener.');
    return;
  }

  childListener = onChildAdded(latestQuery, (snap) => {
    const data = snap.val();
    renderMessage(data);
  }, (err) => {
    console.error('onChildAdded error:', err);
    setStatus('DB listener failed — check rules', true);
  });
  setStatus('Listening for messages', false);
}

/* Detach listeners cleanly */
function detachListeners() {
  if (chatRef && childListener) {
    try { off(chatRef, 'child_added', childListener); } catch(e) { /* ignore */ }
    childListener = null;
  }
}

/* Send message (ensures auth ready) */
async function sendMessage(text, name) {
  if (!text) return;
  if (!auth.currentUser) {
    setStatus('Not signed in — retrying auth...', true);
    const ok = await trySignInAnonymous(1);
    if (!ok) {
      alert('Unable to sign in anonymously. Please enable Anonymous Auth in Firebase console.');
      return;
    }
  }

  // ensure chatRef exists and listeners attached
  if (!chatRef) chatRef = ref(db, CHAT_PATH);

  try {
    await push(chatRef, {
      uid: auth.currentUser?.uid || null,
      name,
      text,
      role: 'client',
      ts: serverTimestamp()
    });
    setStatus('Message sent', false);
  } catch (err) {
    console.error('Send failed:', err);
    // show more helpful messages
    if (err && err.code === 'PERMISSION_DENIED') {
      alert('Send failed: permission denied. Check Realtime DB rules and auth.');
      setStatus('Send failed: permission_denied', true);
    } else {
      alert('Send failed. See console for details.');
    }
  }
}

/* Wire form */
if (form) {
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const text = (inputMsg.value || '').trim();
    if (!text) return;
    const name = (inputName.value || '').trim() || 'Guest';
    if (text.length > 1000) { alert('Message too long'); return; }
    await sendMessage(text, name);
    inputMsg.value = '';
  });
}

/* React to auth state */
onAuthStateChanged(auth, async (user) => {
  if (user) {
    console.log('Auth state changed: signed in', user.uid, user.isAnonymous ? '(anonymous)' : '');
    setStatus('Signed in — attaching listeners...', false);
    await attachListenersSafely();
  } else {
    console.warn('Auth state: NOT signed in. Attempting anonymous sign-in...');
    setStatus('Attempting anonymous sign-in...', true);
    const ok = await trySignInAnonymous(2);
    if (ok) {
      // attach will occur via onAuthStateChanged when user becomes available
      console.log('Anonymous sign-in completed; waiting for state change to attach listeners.');
    } else {
      console.error('Anonymous sign-in failed after retries. Check Firebase Auth settings and API key restrictions.');
      setStatus('Anonymous sign-in failed — check console & Firebase settings', true);
    }
  }
});

/* Init: kick off a sign-in attempt quickly */
(async function init() {
  setStatus('Initializing chat...', false);
  // try to sign in; actual attach happens in onAuthStateChanged handler
  await trySignInAnonymous(2);
})();
