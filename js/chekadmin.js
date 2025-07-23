document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('api_token');
    const role = localStorage.getItem('user_role');

    if (!token || role !== 'admin') {
        window.location.href = '/login';
    }
});
