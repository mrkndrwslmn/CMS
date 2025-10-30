// Firebase Authentication Module
import { initializeApp } from 'firebase/app';
import { 
    getAuth, 
    signInWithPopup, 
    GoogleAuthProvider, 
    OAuthProvider,
    TwitterAuthProvider,
    signOut as firebaseSignOut
} from 'firebase/auth';

class FirebaseAuthService {
    constructor() {
        this.firebaseApp = null;
        this.auth = null;
        this.initialized = false;
    }

    /**
     * Initialize Firebase Authentication
     */
    async initialize() {
        if (this.initialized) {
            return;
        }

        try {
            // Firebase config is provided by the server in window.firebaseConfig
            const firebaseConfig = {
                apiKey: window.firebaseConfig?.apiKey,
                authDomain: window.firebaseConfig?.authDomain,
                projectId: window.firebaseConfig?.projectId,
                storageBucket: window.firebaseConfig?.storageBucket,
                messagingSenderId: window.firebaseConfig?.messagingSenderId,
                appId: window.firebaseConfig?.appId,
                measurementId: window.firebaseConfig?.measurementId,
            };

            // Validate configuration
            if (!firebaseConfig.apiKey || !firebaseConfig.authDomain) {
                console.error('Missing Firebase configuration. Make sure FIREBASE_WEB_API_KEY is set in .env');
                console.error('Available config:', window.firebaseConfig);
                throw new Error('Firebase configuration is missing. Please configure Firebase in your .env file.');
            }

            // Initialize Firebase
            this.firebaseApp = initializeApp(firebaseConfig);
            this.auth = getAuth(this.firebaseApp);
            this.initialized = true;

        } catch (error) {
            console.error('Failed to initialize Firebase Auth:', error);
            throw error;
        }
    }

    /**
     * Sign in with Google
     */
    async signInWithGoogle() {
        await this.initialize();

        try {
            const provider = new GoogleAuthProvider();
            provider.addScope('profile');
            provider.addScope('email');

            const result = await signInWithPopup(this.auth, provider);
            const idToken = await result.user.getIdToken();

            return await this.sendTokenToBackend(idToken);
        } catch (error) {
            console.error('Google sign-in error:', error);
            throw this.handleAuthError(error);
        }
    }

    /**
     * Sign in with Apple
     */
    async signInWithApple() {
        await this.initialize();

        try {
            const provider = new OAuthProvider('apple.com');
            provider.addScope('email');
            provider.addScope('name');

            const result = await signInWithPopup(this.auth, provider);
            const idToken = await result.user.getIdToken();

            return await this.sendTokenToBackend(idToken);
        } catch (error) {
            console.error('Apple sign-in error:', error);
            throw this.handleAuthError(error);
        }
    }

    /**
     * Sign in with Twitter
     */
    async signInWithTwitter() {
        await this.initialize();

        try {
            const provider = new TwitterAuthProvider();

            const result = await signInWithPopup(this.auth, provider);
            const idToken = await result.user.getIdToken();

            return await this.sendTokenToBackend(idToken);
        } catch (error) {
            console.error('Twitter sign-in error:', error);
            throw this.handleAuthError(error);
        }
    }

    /**
     * Link account (for logged-in users)
     */
    async linkAccount(provider) {
        await this.initialize();

        try {
            // First, notify backend that we're in linking mode
            const linkResponse = await fetch('/auth/firebase/link/initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            });

            if (!linkResponse.ok) {
                const data = await linkResponse.json();
                throw new Error(data.message || 'Failed to initiate account linking');
            }

            // Then proceed with the selected provider
            switch (provider) {
                case 'google':
                    return await this.signInWithGoogle();
                case 'apple':
                    return await this.signInWithApple();
                case 'twitter':
                    return await this.signInWithTwitter();
                default:
                    throw new Error('Unsupported provider');
            }
        } catch (error) {
            console.error('Account linking error:', error);
            throw error;
        }
    }

    /**
     * Send ID token to backend for verification and session creation
     */
    async sendTokenToBackend(idToken) {
        try {
            // Show processing message
            this.showSuccess('Verifying credentials...');
            
            const response = await fetch('/auth/firebase/callback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ idToken }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Authentication failed');
            }

            return data;
        } catch (error) {
            console.error('Backend authentication error:', error);
            throw error;
        }
    }

    /**
     * Sign out
     */
    async signOutUser() {
        if (!this.initialized) {
            return;
        }

        try {
            await firebaseSignOut(this.auth);
        } catch (error) {
            console.error('Firebase sign-out error:', error);
        }
    }

    /**
     * Handle authentication errors
     */
    handleAuthError(error) {
        let message = 'Authentication failed. Please try again.';

        switch (error.code) {
            case 'auth/popup-closed-by-user':
                message = 'Sign-in was cancelled. Please try again.';
                break;
            case 'auth/popup-blocked':
                message = 'Pop-up was blocked by your browser. Please allow pop-ups and try again.';
                break;
            case 'auth/account-exists-with-different-credential':
                message = 'An account already exists with the same email but different sign-in credentials.';
                break;
            case 'auth/invalid-credential':
                message = 'Invalid credentials. Please try again.';
                break;
            case 'auth/operation-not-allowed':
                message = 'This sign-in method is not enabled. Please contact support.';
                break;
            case 'auth/user-disabled':
                message = 'Your account has been disabled. Please contact support.';
                break;
            case 'auth/user-not-found':
                message = 'No account found. Please sign up first.';
                break;
            case 'auth/network-request-failed':
                message = 'Network error. Please check your connection and try again.';
                break;
            default:
                if (error.message) {
                    message = error.message;
                }
        }

        return new Error(message);
    }

    /**
     * Show loading state on button
     */
    setButtonLoading(button, loading = true, message = 'Signing in...') {
        if (loading) {
            button.disabled = true;
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i>${message}`;
        } else {
            button.disabled = false;
            if (button.dataset.originalText) {
                button.innerHTML = button.dataset.originalText;
            }
        }
    }

    /**
     * Update button loading message
     */
    updateButtonMessage(button, message) {
        if (button.disabled && button.dataset.originalText) {
            button.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i>${message}`;
        }
    }

    /**
     * Show error message
     */
    showError(message) {
        // Try to find an error container on the page
        const errorContainer = document.getElementById('auth-error');
        if (errorContainer) {
            errorContainer.textContent = message;
            errorContainer.classList.remove('hidden');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                errorContainer.classList.add('hidden');
            }, 5000);
        } else {
            alert(message);
        }
    }

    /**
     * Show success message
     */
    showSuccess(message) {
        const successContainer = document.getElementById('auth-success');
        if (successContainer) {
            successContainer.textContent = message;
            successContainer.classList.remove('hidden');
        }
    }
}

// Create singleton instance
const firebaseAuthService = new FirebaseAuthService();

// Export functions for use in other modules and inline scripts
export const signInWithGoogle = () => firebaseAuthService.signInWithGoogle();
export const signInWithApple = () => firebaseAuthService.signInWithApple();
export const signInWithTwitter = () => firebaseAuthService.signInWithTwitter();
export const linkAccount = (provider) => firebaseAuthService.linkAccount(provider);
export const signOut = () => firebaseAuthService.signOutUser();

// Also export the service itself
export { firebaseAuthService };

// Make it available globally
window.firebaseAuthService = firebaseAuthService;

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        firebaseAuthService.initialize().catch(console.error);
    });
} else {
    firebaseAuthService.initialize().catch(console.error);
}
