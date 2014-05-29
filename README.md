Yet Another Cleaner - clk.php
=============================================

Batch script for content curation in malware "clk.php"

Some host provider in Spain is allowing to multiply some malware in some of my websites.

It infects index, home in .html and .php files. Even some .js files.

Here's a batch to delete the string which prints the suspicious javascript call in client browser.

### **Quick start**

Just upload the main PHP file and configure the root directory to start the batch ($strDirPathToCheck)

```php
// configure the directory to check and cure
$strDirPathToCheck = './';
```

### **Checked files**

Tested only for the infected files 
* .php
* .js
* .htm and html


### **Result**

Final with 2 arrays with infected and cured files
The cured files edited and deleted the malware script
