/* ─────────────────────────────────────────────
   LIVE PASS — Firebase initialization (shared)
   Include AFTER the Firebase SDK scripts:
     <script type="module" src="/firebase-init.js"></script>
   ───────────────────────────────────────────── */
import { initializeApp } from 'https://www.gstatic.com/firebasejs/12.12.0/firebase-app.js';
import { getAuth, onAuthStateChanged, createUserWithEmailAndPassword, signInWithEmailAndPassword, signOut, sendPasswordResetEmail }
  from 'https://www.gstatic.com/firebasejs/12.12.0/firebase-auth.js';
import { getFirestore, doc, getDoc, setDoc, updateDoc, collection, addDoc, getDocs, query, orderBy, limit, where, deleteDoc, serverTimestamp }
  from 'https://www.gstatic.com/firebasejs/12.12.0/firebase-firestore.js';
import { getStorage, ref as storageRef, uploadBytes, getDownloadURL }
  from 'https://www.gstatic.com/firebasejs/12.12.0/firebase-storage.js';

const firebaseConfig = {
  apiKey: "AIzaSyAoEzBo25_Y-Xpda6uuTMZXwgzg2kLVHT4",
  authDomain: "livepass-96f7b.firebaseapp.com",
  projectId: "livepass-96f7b",
  storageBucket: "livepass-96f7b.firebasestorage.app",
  messagingSenderId: "574964996020",
  appId: "1:574964996020:web:5b3c702c88c5717c90c4e1"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const db = getFirestore(app);
const storage = getStorage(app);

// ─── Export to window for inline <script> usage ───
window.FB = {
  app, auth, db, storage,
  // Auth helpers
  onAuthStateChanged,
  createUserWithEmailAndPassword,
  signInWithEmailAndPassword,
  signOut,
  sendPasswordResetEmail,
  // Firestore helpers
  doc, getDoc, setDoc, updateDoc,
  collection, addDoc, getDocs, query, orderBy, limit, where, deleteDoc,
  serverTimestamp,
  // Storage helpers
  storageRef, uploadBytes, getDownloadURL,
};

// ─── Auth state listener: sync to localStorage for backward compat ───
// Only populates localStorage if the fields are EMPTY (doesn't overwrite
// local edits made in Settings).
window.FB.authReady = false;
onAuthStateChanged(auth, async (user) => {
  if (user) {
    window.FB.currentUser = user;
    try {
      const snap = await getDoc(doc(db, 'users', user.uid));
      if (snap.exists()) {
        const data = snap.data();
        // Only set if localStorage is empty — user edits in Settings take priority
        if (!localStorage.getItem('livepass_account_name')) localStorage.setItem('livepass_account_name', data.name || '');
        if (!localStorage.getItem('livepass_role')) localStorage.setItem('livepass_role', data.role || '');
        if (!localStorage.getItem('livepass_avatar') && data.avatar) localStorage.setItem('livepass_avatar', data.avatar);
        if (!localStorage.getItem('livepass_plan')) localStorage.setItem('livepass_plan', data.plan || 'free');
        // NOBBY = promo BIZ account
        const acctName = (data.name || localStorage.getItem('livepass_account_name') || '').toUpperCase();
        if (acctName === 'NOBBY') {
          localStorage.setItem('livepass_plan', 'biz');
          if (data.plan !== 'biz') updateDoc(doc(db, 'users', user.uid), { plan: 'biz' }).catch(() => {});
        }
        localStorage.setItem('livepass_uid', user.uid);
      }
    } catch (e) { console.warn('FB profile load error:', e); }
  } else {
    window.FB.currentUser = null;
    localStorage.removeItem('livepass_uid');
  }
  window.FB.authReady = true;
  window.dispatchEvent(new CustomEvent('fb-auth-ready', { detail: { user } }));
});

console.log('🔥 Firebase initialized — project: livepass-96f7b');
