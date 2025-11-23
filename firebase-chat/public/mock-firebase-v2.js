// mock-firebase.js - Server-side JSON File Version

/* ====== MOCK DATABASE (File-Based) ====== */
const API_URL = 'api.php';

// Helper to fetch data
async function getDbData() {
    try {
        const res = await fetch(API_URL);
        return await res.json();
    } catch (e) {
        console.error("MockDB Read Error:", e);
        return {};
    }
}

// Helper to write data
async function updateDbData(path, value) {
    try {
        const payload = {};
        payload[path] = value;

        await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
    } catch (e) {
        console.error("MockDB Write Error:", e);
    }
}

export function initializeApp(config) {
    console.log("MOCK FIREBASE (FILE-BASED) ACTIVE");
    return { name: "[DEFAULT]" };
}

export function getDatabase(app) {
    return { type: "mock-db" };
}

export function ref(db, path) {
    return { path };
}

export function push(ref, data) {
    const newKey = "msg_" + Date.now() + "_" + Math.random().toString(36).substr(2, 9);
    const fullPath = `${ref.path}/${newKey}`;

    // Write to server
    updateDbData(fullPath, data).then(() => {
        console.log("MockDB: Pushed to", fullPath);
    });

    return { key: newKey };
}

export function onChildAdded(refOrQuery, callback, errorCallback) {
    // Handle both ref and query objects
    const actualRef = refOrQuery.path ? refOrQuery : refOrQuery;

    let knownKeys = new Set();
    let isActive = true;

    const checkNew = async () => {
        if (!isActive) return; // Don't run if listener was detached

        try {
            const allData = await getDbData();

            // Navigate to the ref path in the JSON object
            const parts = actualRef.path.split('/');
            let current = allData;
            for (const part of parts) {
                if (current && current[part]) {
                    current = current[part];
                } else {
                    current = null;
                    break;
                }
            }

            const dataAtRef = current || {};

            Object.keys(dataAtRef).forEach(key => {
                if (!knownKeys.has(key)) {
                    knownKeys.add(key);
                    callback({
                        val: () => dataAtRef[key],
                        key: key
                    });
                }
            });
        } catch (error) {
            if (errorCallback) errorCallback(error);
        }
    };

    // Poll every 1 second
    const interval = setInterval(checkNew, 1000);
    checkNew(); // Run immediately

    // Return cleanup function
    return () => {
        isActive = false;
        clearInterval(interval);
        knownKeys.clear();
    };
}

export function off(refOrQuery, eventType, callback) {
    // In our mock, the cleanup is handled by calling the unsubscribe function
    // returned by onChildAdded. This function exists for API compatibility.
    // The actual cleanup happens when the unsubscribe function is called.
    if (typeof callback === 'function') {
        callback();
    }
}

export function serverTimestamp() {
    return Date.now();
}

export function query(ref, ...constraints) {
    // For mock, just return the ref with constraints attached
    return { ...ref, constraints };
}

export function limitToLast(limit) {
    // Return a constraint object
    return { type: 'limitToLast', value: limit };
}

export function get(ref) {
    return getDbData().then(allData => {
        const parts = ref.path.split('/');
        let current = allData;
        for (const part of parts) {
            if (current && current[part]) {
                current = current[part];
            } else {
                current = null;
                break;
            }
        }
        return {
            val: () => current,
            exists: () => current !== null && current !== undefined
        };
    });
}

/* ====== MOCK AUTH ====== */
export function getAuth(app) {
    return { currentUser: null };
}

export function signInWithPopup(auth, provider) {
    return new Promise((resolve) => {
        const user = {
            uid: "mock-user-123",
            displayName: "Mock User",
            email: "mock@example.com"
        };
        sessionStorage.setItem("mock_auth_user", JSON.stringify(user));
        if (auth._onAuthStateChanged) auth._onAuthStateChanged(user);
        resolve({ user });
    });
}

export function signInAnonymously(auth) {
    return new Promise((resolve) => {
        const user = {
            uid: "anon-" + Math.random().toString(36).substr(2, 9),
            isAnonymous: true
        };
        sessionStorage.setItem("mock_auth_user", JSON.stringify(user));
        if (auth._onAuthStateChanged) auth._onAuthStateChanged(user);
        resolve({ user });
    });
}

export function signOut(auth) {
    return new Promise((resolve) => {
        sessionStorage.removeItem("mock_auth_user");
        if (auth._onAuthStateChanged) auth._onAuthStateChanged(null);
        resolve();
    });
}

export function onAuthStateChanged(auth, callback) {
    auth._onAuthStateChanged = callback;

    // Check initial state
    const stored = sessionStorage.getItem("mock_auth_user");
    if (stored) {
        callback(JSON.parse(stored));
    } else {
        callback(null);
    }

    return () => { auth._onAuthStateChanged = null; };
}

export class GoogleAuthProvider { }
