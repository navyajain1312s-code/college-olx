// admin.js - WhatsApp-style admin panel with contacts sidebar
import { initializeApp } from "./mock-firebase-v2.js";
import {
    getDatabase, ref, push, onChildAdded, off, serverTimestamp, get
} from "./mock-firebase-v2.js";
import {
    getAuth, signInWithPopup, signOut, onAuthStateChanged, GoogleAuthProvider, signInAnonymously
} from "./mock-firebase-v2.js";

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
const chatStatusArea = document.getElementById('chatStatusArea');
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
let currentBuyerName = null; // Currently selected buyer name
let currentBuyerId = null; // Currently selected buyer ID
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
}

/* ====== LOAD CONVERSATIONS ====== */
function loadAllConversations(shopId) {
    // Detach existing listener if any
    if (childListener) {
        console.log('Detaching existing listener before reloading for:', shopId);
        try {
            childListener(); // Call the unsubscribe function
        } catch (e) {
            console.warn('Error detaching listener:', e);
        }
        childListener = null;
    }

    sellerShopId = shopId;

    // Clear existing UI
    contactsList.innerHTML = '';
    allMessages = [];
    contacts.clear();

    console.log('Scanning for buyer conversations under:', `chats/${shopId}/`);
    setStatus('Loading conversations...');

    // Fetch all data and scan for buyer subdirectories
    const sellerPath = `chats/${shopId}`;

    // Use get() to fetch the entire seller's chat data
    const sellerRef = ref(db, sellerPath);
    get(sellerRef).then(snapshot => {
        const sellerData = snapshot.val();

        if (!sellerData) {
            console.log('No conversations found for seller:', shopId);
            setStatus('No conversations yet');
            return;
        }

        // Iterate through buyer subdirectories
        Object.keys(sellerData).forEach(buyerId => {
            const buyerData = sellerData[buyerId];
            if (buyerData && buyerData.messages) {
                // Set up listener for this buyer's messages
                setupBuyerListener(shopId, buyerId);
            }
        });

        setStatus('Loaded conversations');
    }).catch(err => {
        console.error('Error loading conversations:', err);
        setStatus('Error loading conversations', true);
    });
}

function setupBuyerListener(shopId, buyerId) {
    const buyerMessagesPath = `chats/${shopId}/${buyerId}/messages`;
    const buyerRef = ref(db, buyerMessagesPath);

    console.log('Setting up listener for:', buyerMessagesPath);

    // Listen for messages from this buyer
    const unsubscribe = onChildAdded(buyerRef, (snapshot) => {
        const msg = snapshot.val();
        const key = snapshot.key;

        if (!msg || !msg.text) return;

        // Add buyer ID to message for tracking
        const msgWithBuyer = { ...msg, key, buyerId };
        allMessages.push(msgWithBuyer);

        // Update contact info for this buyer
        const buyerName = msg.buyerName || msg.name || buyerId;
        updateContact({ ...msg, buyerName, buyerId });

        // If this message belongs to currently open chat, append it
        if (currentBuyerName && currentBuyerName === buyerName) {
            renderMessage(msg);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
    });

    // Store unsubscribe function (we'll need to manage multiple listeners)
    // For now, we'll just track the first one in childListener
    if (!childListener) {
        childListener = unsubscribe;
    }
}


function updateContact(msg) {
    const buyerName = msg.buyerName || msg.name || 'Unknown';
    const buyerId = msg.buyerId || 'unknown';

    // Update contact info
    contacts.set(buyerName, {
        name: buyerName,
        buyerId: buyerId,
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

    // Get buyer ID from contacts
    const contact = contacts.get(buyerName);
    currentBuyerId = contact ? contact.buyerId : null;

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

    // Use classes from admins.php <style> block: .message-item.client / .message-item.seller
    div.className = `message-item ${isMe ? 'seller' : 'client'}`;

    const time = new Date(m.ts).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const senderName = m.name || (isMe ? 'You' : 'Buyer');

    div.innerHTML = `
        <div class="message-bubble">
            <div class="message-sender">${escapeHtml(senderName)}</div>
            <div class="message-text">${escapeHtml(m.text)}</div>
            <div class="message-time">
                ${time}
                ${isMe ? '<span>✓✓</span>' : ''}
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
    if (!text || !currentBuyerName || !currentBuyerId || !sellerShopId) {
        console.warn('Cannot send: missing required info', { text, currentBuyerName, currentBuyerId, sellerShopId });
        return;
    }

    // Send to buyer-specific path
    const chatPath = `chats/${sellerShopId}/${currentBuyerId}/messages`;
    const messagesRef = ref(db, chatPath);

    push(messagesRef, {
        text: text,
        role: 'seller',
        buyerName: currentBuyerName,
        name: window.SELLER_USERNAME || 'Seller',
        ts: serverTimestamp()
    });

    replyInput.value = '';
});

replyInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') sendBtn.click();
});

/* ====== AUTH STATE MANAGEMENT ====== */
onAuthStateChanged(auth, (user) => {
    if (!chatStatusArea) return;

    chatStatusArea.innerHTML = ''; // Clear only the chat status area

    if (user) {
        // Show connection status
        const statusSpan = document.createElement('span');
        statusSpan.style.fontSize = '12px';
        statusSpan.style.color = '#e0e0e0';
        statusSpan.textContent = 'Chat Connected';
        chatStatusArea.appendChild(statusSpan);

        // Add Refresh Button
        const refreshBtn = document.createElement('button');
        refreshBtn.textContent = '🔄 Refresh';
        refreshBtn.className = 'btn btn-secondary';
        refreshBtn.style.fontSize = '12px';
        refreshBtn.onclick = () => {
            if (sellerShopId) loadAllConversations(sellerShopId);
            else location.reload();
        };
        chatStatusArea.appendChild(refreshBtn);

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
            // Only show manual login if auto fails
            const loginBtn = document.createElement('button');
            loginBtn.textContent = 'Enable Chat';
            loginBtn.className = 'btn btn-primary';
            loginBtn.onclick = () => signInWithPopup(auth, provider);
            chatStatusArea.appendChild(loginBtn);
        });
    }
});

init();
