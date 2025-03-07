## PHP Code Injection?

PHP Code Injection is a vulnerability that allows an attacker to execute arbitrary PHP code on a server by injecting malicious input. This usually happens when **user input is improperly handled** and passed to functions like `eval()`, `system()`, `exec()`, or `include()`.

### How It Works

An attacker can manipulate input parameters to execute unintended PHP commands, leading to:

- Data leakage (accessing sensitive files)
- System command execution (gaining control of the server)
- Complete server compromise

### Example of PHP Code Injection Vulnerability

A poorly secured script might include:

```
if (isset($_GET['cmd'])) {
    eval($_GET['cmd']); // ⚠️ Vulnerable to code injection
}
 
```

An attacker could execute:

```
http://127.0.0.1/phpcode.php?cmd=phpinfo()](http://example.com/vuln.php?cmd=phpinfo()) 
```

or even execute system commands if `eval()` allows it:

```
http://127.0.0.1/phpcode.php?cmd=system('ls');
```

### **Mitigation Strategies**

To prevent PHP Code Injection:

✅ **Avoid `eval()` and similar functions**

✅ **Use allowlists** (Only permit specific commands)

✅ **Sanitize and validate input**

✅ **Use prepared statements for database queries**

✅ **Disable dangerous PHP functions in `php.ini`**

✅**disable exec(), shell_exec(), passthru(), and system() functions in PHP configuration unless it is absolutely necessary to use them. You can also create a whitelist of accepted commands/arguments.**
