<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-12
 */

if ($argc !== 3) {
    echo $usage;
    echo 'command: delete [user id]' . PHP_EOL;
    exit(1);
}

$validUserIds = array_keys($users);
$inputUserId = (int) $argv[2];

//begin of validation
if (!isset($validUserIds[$inputUserId])) {
    throw new Exception(
        'invalid name "' . $inputUserId . '" provided'
    );
}
//end of validation

//begin delete user
reset($users);

$lines = explode("\n", file_get_contents($pathToUsersPhp));
$content = array();

foreach ($lines as $line) {
    if ($line == '$users[0][\'channels\'] = array(0);') {
        $content[] = $line;
        $content[] = '';
        break;
    } else {
        $content[] = $line;
    }
}

unset($users[0]);
if (empty($users)) {
    echo 'nothing to delete' . PHP_EOL;
} else {
    unset($users[$inputUserId]);
    $users = array_values($users);

    if (!empty($users)) {
        foreach ($users as $id => $user) {
            $content[] = '// updated - ' . date('Y-m-d H:i:s');
            $content[] = '$users[' . $id . '] = array();';
            $content[] = '$users[' . $id . '][\'userRole\'] = ' . $roles[$user['userRole']] . ';';
            $content[] = '$users[' . $id . '][\'userName\'] = \'' . $user['userName'] . '\';';
            $content[] = '$users[' . $id . '][\'password\'] = \'' . $user['password'] . '\';';
            $content[] = '$users[' . $id . '][\'channels\'] = array(' . implode(',', $user['channels']) . ');';
        }
    }

    if (file_put_contents($pathToUsersPhp, implode("\n", $content)) === false) {
        echo 'could not write content to: "' . $pathToUsersPhp . '"' . PHP_EOL;
    } else {
        echo 'done' . PHP_EOL;
    }
}
//end delete user
