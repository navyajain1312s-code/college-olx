// mock-firebase.js - Local mock for Firebase using localStorage

console.log('%c MOCK FIREBASE ACTIVE ', 'background: #222; color: #bada55; font-size: 20px');

// --- Mock App ---
export function initializeApp(config) {
    console.log('Mock initializeApp', config);
    return { name: '[DEFAULT]' };
}

// --- Mock Auth ---
class MockAuth {
    constructor() {
        this.currentUser = JSON.parse(localStorage.getItem('mock_auth_user') || 'null');
        this.listeners = [];
    }

    onAuthStateChanged(cb) {
        this.listeners.push(cb);
        // fire immediately
        setTimeout(() => cb(this.currentUser), 0);
        return () => { this.listeners = this.listeners.filter(l => l !== cb); };
    }

    _setUser(user) {
        this.currentUser = user;
        localStorage.setItem('mock_auth_user', JSON.stringify(user));
        this.listeners.forEach(cb => cb(user));
    }

    async signInAnonymously() {
        console.log('Mock signInAnonymously');
        const user = {
            uid: 'anon_' + Math.random().toString(36).slice(2),
            isAnonymous: true,
            displayName: 'Guest',
            email: null
        };
        this._setUser(user);
        return { user };
    }

    async signInWithPopup() {
        console.log('Mock signInWithPopup');
        const user = {
            uid: 'admin_' + Math.random().toString(36).slice(2),
            isAnonymous: false,
            displayName: 'Admin User',
            email: 'admin@example.com',
            photoURL: 'https://via.placeholder.com/50'
        };
        this._setUser(user);
        return { user };
    }

    async signOut() {
        console.log('Mock signOut');
        this._setUser(null);
    }
}

const authInstance = new MockAuth();
export function getAuth() { return authInstance; }
export function onAuthStateChanged(auth, cb) { return auth.onAuthStateChanged(cb); }
export function signInAnonymously(auth) { return auth.signInAnonymously(); }
export function signInWithPopup(auth, provider) { return auth.signInWithPopup(); }
export function signOut(auth) { return auth.signOut(); }
export class GoogleAuthProvider { }

// --- Mock Database ---
class MockDatabase {
    constructor() { }
}
const dbInstance = new MockDatabase();

export function getDatabase() { return dbInstance; }

export function ref(db, path) {
    return { path }; // simple ref object
}

// Helper to read/write DB from localStorage
function getDbData() {
    return JSON.parse(localStorage.getItem('mock_rtdb_data') || '{}');
}
function setDbData(data) {
    localStorage.setItem('mock_rtdb_data', JSON.stringify(data));
    // dispatch storage event for cross-tab sync
    window.dispatchEvent(new Event('storage'));
}

export function push(ref, data) {
    return new Promise((resolve) => {
        const dbData = getDbData();
        const path = ref.path;
        if (!dbData[path]) dbData[path] = {};

        const newId = 'msg_' + Date.now() + Math.random().toString(36).slice(2);
        dbData[path][newId] = data;

        setDbData(dbData);
        console.log('Mock push', path, data);
        resolve({ key: newId });
    });
}

export function onChildAdded(query, cb, cancelCb) {
    const path = query.path; // query is just ref in our simple mock
    console.log('Mock onChildAdded listener attached to', path);

    // 1. Initial load
    const dbData = getDbData();
    const items = dbData[path] || {};
    Object.keys(items).forEach(key => {
        cb({ val: () => items[key], key });
    });

    // 2. Listen for changes
    const handler = () => {
        const newData = getDbData();
        const newItems = newData[path] || {};
        // In a real implementation we'd track what we've already sent.
        // For this simple mock, we might re-send or miss things if we aren't careful.
        // Let's just check for NEW keys since we last checked? 
        // Actually, simpler: let's just poll or rely on the fact that 'push' updates localStorage.
        // But 'storage' event only fires on OTHER tabs.
        // We need a way to trigger this listener when *we* push too.

        // To keep it simple: we won't implement perfect onChildAdded logic here for *existing* items 
        // if we re-read everything. 
        // Instead, let's just hook into the 'push' operation if possible, or use a polling interval?
        // Polling is easiest for a robust mock without complex state.
    };

    // Better approach for mock: override setDbData to dispatch a custom event
    const localHandler = (e) => {
        // This is a bit hacky but works for a demo. 
        // When storage changes, we re-read. But onChildAdded should only fire for NEW items.
        // We'll keep a set of seen keys.
    };

    let seenKeys = new Set(Object.keys(items));

    const checkNew = () => {
        const currentData = getDbData();
        const currentItems = currentData[path] || {};
        Object.keys(currentItems).forEach(key => {
            if (!seenKeys.has(key)) {
                seenKeys.add(key);
                cb({ val: () => currentItems[key], key });
            }
        });
    };

    // Poll every 500ms to catch local and remote changes
    const interval = setInterval(checkNew, 500);

    return () => clearInterval(interval);
}

export function off() {
    // no-op for mock
}

export function query(ref) { return ref; }
export function limitToLast() { return {}; }
export function serverTimestamp() { return Date.now(); }
export function get(ref) {
    const dbData = getDbData();
    const val = dbData[ref.path] || null;
    return Promise.resolve({
        exists: () => val !== null,
        val: () => val
    });
}
