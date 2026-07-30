// Helpers de fetch contra la API (proxy de Vite reenvía /api → http://localhost:3000)
const TOKEN_KEY = 'maletin_token';

export const getToken = () => localStorage.getItem(TOKEN_KEY);
export const setToken = (t) => localStorage.setItem(TOKEN_KEY, t);
export const clearToken = () => localStorage.removeItem(TOKEN_KEY);

async function request(method, path, body) {
    const headers = {};
    const token = getToken();
    if (token) headers['Authorization'] = `Bearer ${token}`;
    if (body) headers['Content-Type'] = 'application/json';

    const res = await fetch(path, {
        method,
        headers,
        body: body ? JSON.stringify(body) : undefined,
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(data.message || `HTTP ${res.status}`);
    return data;
}

export const api = {
    register: (name, email, password) => request('POST', '/api/users/register', { name, email, password }),
    login:    (email, password)        => request('POST', '/api/users/login', { email, password }),

    listItems: (cat) => request('GET', cat && cat !== 'all' ? `/api/items?cat=${cat}` : '/api/items'),

    getBriefcase:   ()           => request('GET', '/api/briefcase'),
    saveBriefcase:  (state)      => request('PUT', '/api/briefcase', state),
    clearBriefcase: ()           => request('DELETE', '/api/briefcase'),
    resizeBriefcase:(w, h)       => request('PATCH', '/api/briefcase/size', { w, h }),
    addItem:        (placement)  => request('POST', '/api/briefcase/items', placement),
    moveItem:       (idx, patch) => request('PATCH', `/api/briefcase/items/${idx}`, patch),
    removeItem:     (idx)        => request('DELETE', `/api/briefcase/items/${idx}`),
};
