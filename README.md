# Complex Test App

A PHP app with multiple security vulnerabilities for testing Nile Patch.

## Vulnerabilities

1. **SQL Injection** (login.php, includes/auth.php, includes/functions.php)
   - Login form concatenates user input into SQL query
   - Search function concatenates user input into LIKE clause

2. **XSS** (index.php, includes/functions.php)
   - Search term echoed without escaping
   - User data displayed without escaping

3. **Local File Inclusion** (index.php, includes/functions.php)
   - `page` parameter used in include() without validation

4. **Insecure Deserialization** (includes/functions.php)
   - `unserialize()` on user-controlled data

5. **Hardcoded Credentials** (config/db.php)
   - Database password in source code

6. **No CSRF Protection** (login.php, savePreference)
   - Forms lack CSRF tokens

7. **No Password Hashing** (includes/auth.php)
   - Passwords stored and compared in plaintext

## Expected fixes

1. Use prepared statements with parameterized queries
2. Escape output with htmlspecialchars()
3. Validate and whitelist allowed page names
4. Replace unserialize with json_decode or safe deserialization
5. Move credentials to environment variables
6. Add CSRF tokens to forms
7. Use password_hash() and password_verify()
