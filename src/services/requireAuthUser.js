import jwt_decode from 'jwt-decode';

export default function requireAuthUser(to, from, next) {
    const token = localStorage.getItem('token');
    if (token) {
        const decoded = jwt_decode(token);
        const now = Date.now().valueOf() / 1000; // convert to Unix timestamp
        if (decoded.exp >= now) {
            next();
        } else {
            // expired token
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            next('/register');
        }
    } else if (!token) {
        // no token
        next('/');
    }
}
