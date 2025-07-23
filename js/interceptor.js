const originalFetch = window.fetch;

window.fetch = function(url, options = {}) {
    const token = localStorage.getItem('auth_token');

    if (token && url.startsWith('/api/')) {
        options.headers = {
            ...options.headers,
            'Authorization': token,
            'Accept': 'application/json'
        };
    }

    return originalFetch(url, options).then(response => {
        if (response.status === 401 && !url.endsWith('/api/login')) {
            localStorage.clear();
            window.location.href = '/login';
        }
        return response;
    });
};
