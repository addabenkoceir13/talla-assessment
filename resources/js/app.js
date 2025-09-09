import './bootstrap';
import './echo'; 

console.log('🚀 App.js loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Echo status:', window.Echo ? '✅ Loaded' : '❌ Not loaded');
    
    if (window.Echo) {
        console.log('✅ Echo ready for use');
    } else {
        console.error('❌ Echo failed to load - check console for errors');
    }
});