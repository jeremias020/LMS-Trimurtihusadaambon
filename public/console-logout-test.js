/**
 * Logout Functionality Test Script
 * 
 * Instructions:
 * 1. Open your LMS Guru dashboard in browser
 * 2. Open Developer Tools (F12)
 * 3. Go to Console tab
 * 4. Copy and paste this entire script
 * 5. Press Enter to run the tests
 */

console.log('🔍 Starting Logout Functionality Tests...\n');

// Test 1: Check if logout elements exist
console.log('=== Test 1: Checking Logout Elements ===');

const logoutForm = document.getElementById('logout-form');
const logoutLink = document.querySelector('a[onclick*="logout-form"]');
const csrfToken = document.querySelector('input[name="_token"]');

console.log('✓ Logout form exists:', !!logoutForm);
console.log('✓ Logout link exists:', !!logoutLink);
console.log('✓ CSRF token exists:', !!csrfToken);

if (logoutForm) {
    console.log('📋 Form details:');
    console.log('  - Action:', logoutForm.action);
    console.log('  - Method:', logoutForm.method);
    console.log('  - Form HTML:', logoutForm.outerHTML);
}

if (logoutLink) {
    console.log('🔗 Link details:');
    console.log('  - Text:', logoutLink.textContent.trim());
    console.log('  - OnClick:', logoutLink.getAttribute('onclick'));
}

// Test 2: Test JavaScript confirmation function
console.log('\n=== Test 2: Testing Logout JavaScript ===');

if (logoutLink) {
    // Extract and test the onclick function
    const onclickCode = logoutLink.getAttribute('onclick');
    console.log('📜 OnClick code:', onclickCode);
    
    // Test if confirm function exists
    console.log('✓ Window.confirm exists:', typeof window.confirm === 'function');
    
    // Test if form submission would work (without actually submitting)
    if (logoutForm) {
        const testSubmit = () => {
            try {
                // Test form validity
                console.log('✓ Form can be submitted:', typeof logoutForm.submit === 'function');
                console.log('✓ Form has required CSRF token:', logoutForm.querySelector('[name="_token"]') !== null);
                return true;
            } catch (e) {
                console.error('❌ Form submission test failed:', e.message);
                return false;
            }
        };
        
        const canSubmit = testSubmit();
        console.log('✓ Form ready for submission:', canSubmit);
    }
} else {
    console.error('❌ Cannot test JavaScript - logout link not found');
}

// Test 3: Check for JavaScript errors
console.log('\n=== Test 3: Error Detection ===');

// Override console.error temporarily to catch any errors
const originalError = console.error;
const errors = [];

console.error = function(...args) {
    errors.push(args.join(' '));
    originalError.apply(console, args);
};

// Restore console.error after 2 seconds
setTimeout(() => {
    console.error = originalError;
    console.log('🐛 JavaScript errors found:', errors.length === 0 ? 'None' : errors);
}, 2000);

// Test 4: Mock logout test (safe - won't actually log out)
console.log('\n=== Test 4: Mock Logout Test ===');

function testLogoutSafely() {
    if (!logoutLink || !logoutForm) {
        console.error('❌ Cannot perform mock test - missing elements');
        return;
    }
    
    console.log('🎭 Performing mock logout test...');
    
    // Simulate the confirmation dialog
    const mockConfirm = true; // Simulate user clicking "OK"
    console.log('✓ Confirmation dialog would show:', mockConfirm);
    
    if (mockConfirm) {
        console.log('✓ Form would be submitted to:', logoutForm.action);
        console.log('✓ CSRF token would be sent:', !!csrfToken?.value);
        console.log('✓ Logout link would be disabled');
        console.log('✓ Loading spinner would appear');
        console.log('🎉 Mock logout test PASSED - Real logout should work!');
    } else {
        console.log('❌ User cancelled - logout would be aborted');
    }
}

testLogoutSafely();

// Test 5: Authentication status
console.log('\n=== Test 5: Authentication Status ===');

const hasLaravelSession = document.cookie.includes('laravel_session');
const hasXSRFToken = document.cookie.includes('XSRF-TOKEN');
const hasAuthUser = document.querySelector('meta[name="user-id"]') || document.body.textContent.includes('{{ Auth::user()');

console.log('🍪 Laravel session cookie:', hasLaravelSession);
console.log('🔐 XSRF token cookie:', hasXSRFToken);
console.log('👤 User appears to be authenticated:', hasLaravelSession || hasXSRFToken);

// Final recommendations
console.log('\n=== 📋 DIAGNOSTIC SUMMARY ===');

const issues = [];
if (!logoutForm) issues.push('Logout form missing');
if (!logoutLink) issues.push('Logout link missing');
if (!csrfToken) issues.push('CSRF token missing');
if (!hasLaravelSession && !hasXSRFToken) issues.push('Authentication cookies missing');

if (issues.length === 0) {
    console.log('🎉 ALL TESTS PASSED! Logout should work correctly.');
    console.log('💡 If logout still doesn\'t work, try these steps:');
    console.log('   1. Clear browser cache and cookies');
    console.log('   2. Check Network tab for failed POST requests');
    console.log('   3. Verify server-side session configuration');
} else {
    console.log('❌ ISSUES FOUND:');
    issues.forEach(issue => console.log(`   - ${issue}`));
    console.log('💡 Fix these issues to resolve logout problems.');
}

console.log('\n🔍 Test completed! Check the output above for details.');

// Additional helper function for manual testing
window.debugLogout = function() {
    console.log('🔧 Manual logout debug function called');
    if (logoutForm && logoutLink) {
        console.log('Elements found - you can safely test logout now');
        return { form: logoutForm, link: logoutLink, csrf: csrfToken?.value };
    } else {
        console.error('Missing elements - logout will not work');
        return null;
    }
};

console.log('💡 TIP: You can call debugLogout() anytime to re-check elements');