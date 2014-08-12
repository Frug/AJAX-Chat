<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-12
 */

if ($argc !== 6) {
    echo $usage;
    echo 'command: add "login name" "role" "channels" "password"' . PHP_EOL;
    echo 'available roles: ' .
        implode(',', $roles) . PHP_EOL;
    echo 'available channels: ' . implode(',', array_keys($channels)) . PHP_EOL;
    exit(1);
}

$inputName = trim($argv[2]);
$inputRole = trim($argv[3]);
$inputChannels = explode(',', trim($argv[4]));
$inputPassword = trim($argv[5]);

//begin of validation
if (strlen($inputName) < 1) {
    throw new Exception(
        'invalid name "' . $inputName . '" provided'
    );
}

if (strlen($inputRole) < 1) {
    throw new Exception(
        'invalid name "' . $inputRole . '" provided'
    );
} else {
    if (!isset($roles[$inputRole])) {
        throw new Exception(
            'invalid role "' . $inputRole . '" provided'
        );
    }
}

if (strlen($inputPassword) < 1) {
    throw new Exception(
        'invalid name "' . $inputPassword . '" provided'
    );
}

if (empty($inputChannels)) {
    throw new Exception(
        'no channels provided'
    );
}

foreach ($inputChannels as $channel) {
    if (!isset($channels[$channel])) {
        throw new Exception(
            'invalid channel "' . $channel . '" provided'
        );
    }
}
//end of validation

//begin add user
end($users);
$nextKey = (key($users) + 1);
reset($users);

$content = explode("\n", file_get_contents($pathToUsersPhp));

$content[] = '// added - ' . date('Y-m-d H:i:s');
$content[] = '$users[' . $nextKey . '] = array();';
$content[] = '$users[' . $nextKey . '][\'userRole\'] = ' . $roles[$inputRole] . ';';
$content[] = '$users[' . $nextKey . '][\'userName\'] = \'' . $inputName . '\';';
$content[] = '$users[' . $nextKey . '][\'password\'] = \'' . $inputPassword . '\';';
$content[] = '$users[' . $nextKey . '][\'channels\'] = array(' . implode(',', $inputChannels) . ');';

if (file_put_contents($pathToUsersPhp, implode("\n", $content)) === false) {
    echo 'could not write content to: "' . $pathToUsersPhp . '"' . PHP_EOL;
} else {
    echo 'done' . PHP_EOL;
}
//end add user
