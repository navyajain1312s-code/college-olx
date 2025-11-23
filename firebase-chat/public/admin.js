// admin.js - WhatsApp-style admin panel with contacts sidebar
import { initializeApp } from "./mock-firebase.js";
import {
    getDatabase, ref, push, onChildAdded, off, serverTimestamp, get
} from "./mock-firebase.js";
import {
    getAuth, signInWithPopup, signOut, onAuthStateChanged, GoogleAuthProvider, signInAnonymously
} from "./mock-firebase.js";

/* ====== FIREBASE CONFIG ====== */
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

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);
const auth = getAuth(app);
const provider = new GoogleAuthProvider();

/* ====== DOM ELEMENTS ====== */
const contactsList = document.getElementById('contactsList');
const messagesDiv = document.getElementById('messages');
const replyInput = document.getElementById('replyInput');
const sendBtn = document.getElementById('sendBtn');
const authArea = document.getElementById('authArea');
const statusBanner = document.getElementById('statusBanner');
const emptyChat = document.getElementById('emptyChat');
const chatHeader = document.getElementById('chatHeader');
const replySection = document.getElementById('replySection');
const currentChatName = document.getElementById('currentChatName');
const currentAvatar = document.getElementById('currentAvatar');
const currentChatStatus = document.getElementById('currentChatStatus');

/* ====== STATE MANAGEMENT ====== */
let allMessages = []; // Store all messages
let contacts = new Map(); // Map of buyerName -> contact info
let currentBuyerName = null; // Currently selected buyer
let chatRef = null;
let childListener = null;
let sellerShopId = null; // Will be set based on logged-in user

/* ====== STATUS MANAGEMENT ====== */
function setStatus(text, warning = false) {
    if (!statusBanner) return;
    statusBanner.textContent = text;
    statusBanner.style.background = warning ? '#ff4444' : '#00a884';
    statusBanner.style.display = 'block';
    setTimeout(() => {
        statusBanner.style.display = 'none';
    }, 3000);
}

/* ====== INIT ====== */
function init() {
    console.log('Admin init...');

    // Add Refresh Button
    const refreshBtn = document.createElement('button');
    refreshBtn.textContent = '🔄 Refresh Chats';
    refreshBtn.className = 'btn btn-secondary';
    refreshBtn.style.marginLeft = '10px';
    refreshBtn.onclick = () => {
        if (sellerShopId) {
            console.log('Manual refresh for:', sellerShopId);
            loadAllConversations(sellerShopId);
        } else {
            location.reload();
        }
    };
    if (authArea) authArea.appendChild(refreshBtn);
}

/* ====== LOAD CONVERSATIONS ====== */
function loadAllConversations(shopId) {
    if (childListener) {
        console.log('Reloading conversations for:', shopId);
    }

    sellerShopId = shopId;
    chatRef = ref(db, `chats/${shopId}/messages`);

    // Clear existing UI to avoid duplicates if re-running
    contactsList.innerHTML = '';
    allMessages = [];
    contacts.clear();

    console.log('Listening to:', `chats/${shopId}/messages`);
    setStatus('Connecting to chat...');

    childListener = onChildAdded(chatRef, (snapshot) => {
        const msg = snapshot.val();
        const key = snapshot.key;

        if (!msg || !msg.text) return;

        // Add to local store
        allMessages.push({ ...msg, key });

        // Process for contacts list
        updateContact(msg);

        // If this message belongs to currently open chat, append it
        if (currentBuyerName && msg.buyerName === currentBuyerName) {
            renderMessage(msg);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
    });
}

function updateContact(msg) {
    const buyerName = msg.buyerName || msg.name || 'Unknown';

    // Update contact info
    contacts.set(buyerName, {
        name: buyerName,
        lastMsg: msg.text,
        ts: msg.ts,
    });

    renderContacts();
}

function renderContacts() {
    contactsList.innerHTML = '';

    // Sort contacts by timestamp (newest first)
    const sortedContacts = Array.from(contacts.values()).sort((a, b) => b.ts - a.ts);

    sortedContacts.forEach(c => {
        const div = document.createElement('div');
        div.className = `contact-item ${c.name === currentBuyerName ? 'active' : ''}`;
        div.onclick = () => openConversation(c.name);

        const date = new Date(c.ts).toLocaleDateString();

        div.innerHTML = `
            <div class="avatar">${getInitials(c.name)}</div>
            <div class="contact-info">
                <div class="contact-header">
                    <span class="contact-name">${escapeHtml(c.name)}</span>
                    <span class="contact-time">${date}</span>
                </div>
                <div class="contact-preview">${escapeHtml(c.lastMsg)}</div>
            </div>
        `;
        contactsList.appendChild(div);
    });
}

function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
}

function openConversation(buyerName) {
    currentBuyerName = buyerName;

    // Update UI
    emptyChat.style.display = 'none';
    chatHeader.style.display = 'flex';
    messagesDiv.style.display = 'block';
    replySection.style.display = 'flex';

    currentChatName.textContent = buyerName;
    currentAvatar.textContent = getInitials(buyerName);
    currentChatStatus.textContent = 'online'; // Mock status

    // Highlight contact
    renderContacts();

    // Render messages
    renderMessagesForBuyer(buyerName);
}

function renderMessagesForBuyer(buyerName) {
    messagesDiv.innerHTML = '';

    // Filter messages for this buyer
    const chatMsgs = allMessages.filter(m => {
        return m.buyerName === buyerName;
    });

    // Sort by time
    chatMsgs.sort((a, b) => a.ts - b.ts);

    let lastDate = null;

    chatMsgs.forEach(m => {
        // Date divider
        const date = new Date(m.ts).toLocaleDateString();
        if (date !== lastDate) {
            const div = document.createElement('div');
            div.className = 'date-divider';
            div.textContent = date;
            messagesDiv.appendChild(div);
            lastDate = date;
        }
        renderMessage(m);
    });

    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

function renderMessage(m) {
    const div = document.createElement('div');
    const isMe = m.role === 'seller';
    div.className = `msg ${isMe ? 'sent' : 'received'}`;

    const time = new Date(m.ts).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    div.innerHTML = `
        <div class="bubble">
            <div class="txt">${escapeHtml(m.text)}</div>
            <div class="meta">
                ${time}
                ${isMe ? '<span class="tick">✓✓</span>' : ''}
            </div>
        </div>
    `;
    messagesDiv.appendChild(div);
}

function escapeHtml(text) {
    if (!text) return '';
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

/* ====== SEND REPLY ====== */
sendBtn.addEventListener('click', () => {
    const text = replyInput.value.trim();
    if (!text || !currentBuyerName || !sellerShopId) return;

    const chatPath = `chats/${sellerShopId}/messages`;
    const messagesRef = ref(db, chatPath);

    push(messagesRef, {
        text: text,
        role: 'seller',
        buyerName: currentBuyerName, // CRITICAL: Tag message with buyer name so we know who it's for
        ts: serverTimestamp()
    });

    replyInput.value = '';
});

replyInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') sendBtn.click();
});

/* ====== AUTH STATE MANAGEMENT ====== */
onAuthStateChanged(auth, (user) => {
    if (!authArea) return;

    if (user) {
        authArea.innerHTML = `
            <span>Signed in as <strong>${escapeHtml(user.displayName || user.email || 'Admin')}</strong></span>
            <button id="signOutBtn" class="btn btn-secondary">Sign out</button>
        `;

        // Re-add refresh button if lost
        const refreshBtn = document.createElement('button');
        refreshBtn.textContent = '🔄 Refresh Chats';
        refreshBtn.className = 'btn btn-secondary';
        refreshBtn.style.marginLeft = '10px';
        refreshBtn.onclick = () => {
            if (sellerShopId) loadAllConversations(sellerShopId);
            else location.reload();
        };
        authArea.appendChild(refreshBtn);

        const signOutBtn = document.getElementById('signOutBtn');
        if (signOutBtn) {
            signOutBtn.onclick = () => {
                signOut(auth).catch(e => console.error('Sign-out failed', e));
            };
        }
        setStatus('Connected', false);

        // Get seller shop ID from PHP session (passed via window object)
        const sellerUserId = window.SELLER_USER_ID || 1;
        const shopId = `seller-${sellerUserId}`;
        console.log('Loading conversations for seller:', sellerUserId, 'Shop ID:', shopId);
        loadAllConversations(shopId);

    } else {
        // Auto-sign in anonymously if not signed in
        console.log('Admin not signed in. Attempting anonymous auth...');
        setStatus('Authenticating...', true);
        signInAnonymously(auth).catch(e => {
            console.error('Auto-auth failed', e);
            authArea.innerHTML = `
                <button id="signInBtn" class="btn btn-primary">Sign in with Google</button>
            `;
            document.getElementById('signInBtn').onclick = () => signInWithPopup(auth, provider);
        });
    }
});

init();
