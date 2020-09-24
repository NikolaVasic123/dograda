<?php
session_start();

require_once "functions.php";
require_once "PrivilegedUser.php";

$ok = false;
if(isset($_SESSION['username']))
{
    $user = PrivilegedUser::getByUsername($_SESSION['username']);
    if($user !== false && $user->hasPrivilege("Run SQL"))
    {
        $ok = true;
    }
}
if($ok) 
{

 createTable('users', 
 'id INT UNSIGNED AUTO_INCREMENT,
 username VARCHAR(30) NOT NULL,
 password VARCHAR(255) NOT NULL,
 email VARCHAR(255) NOT NULL,
 PRIMARY KEY(id)'
);
createTable('lokali', 
'id INT UNSIGNED AUTO_INCREMENT,
username VARCHAR(30) NOT NULL,
password VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL,
tip CHAR(1) NOT NULL,
mesta INT(5) NOT NULL,
slobodnamesta INT(5) NOT NULL,
vreme VARCHAR(255) NOT NULL,
PRIMARY KEY(id)'
);
createTable('roles', '
id INT UNSIGNED AUTO_INCREMENT,
role_name VARCHAR(200) NOT NULL,
PRIMARY KEY(id)
');

createTable('permissions', '
id INT UNSIGNED AUTO_INCREMENT,
perm_desc VARCHAR(200) NOT NULL,
PRIMARY KEY(id)
');

createTable('role_permissions', '
id INT UNSIGNED AUTO_INCREMENT,
role_id INT UNSIGNED NOT NULL,
perm_id INT UNSIGNED NOT NULL,
PRIMARY KEY(id),
FOREIGN KEY(role_id) REFERENCES roles(id) ON UPDATE CASCADE ON DELETE NO ACTION,
FOREIGN KEY(perm_id) REFERENCES permissions(id) ON UPDATE CASCADE ON DELETE NO ACTION
');

createTable('user_roles', '
id INT UNSIGNED AUTO_INCREMENT,
user_id INT UNSIGNED NOT NULL,
role_id INT UNSIGNED NOT NULL,
PRIMARY KEY(id),
FOREIGN KEY(user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE NO ACTION,
FOREIGN KEY(role_id) REFERENCES roles(id) ON UPDATE CASCADE ON DELETE NO ACTION
');
}else{
    echo "Nema te pristup ovoj stranici !!!";
}
