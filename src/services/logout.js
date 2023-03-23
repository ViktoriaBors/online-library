export default function logout(to, from, next) {
    const token = localStorage.getItem('token');
    const adminRegex = /^\/(admin|user)($|\/.*)/;
    if (token && adminRegex.test(from.path)) {
        localStorage.removeItem('token');
        localStorage.removeItem('admin');
        localStorage.removeItem('user');
        next()
    } else {
        next()
    }
}