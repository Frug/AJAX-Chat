<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-12
 */

unset($users[0]);
//remove initial user since we are not allowed to remove this user
$numberOfUsers = count($users);
$iterator = $numberOfUsers;

echo 'number of users: ' . $numberOfUsers . PHP_EOL;

//@todo implement output styling
if ($numberOfUsers > 0) {
    echo PHP_EOL;
    echo 'id | name | role | channels' . PHP_EOL;
    echo '----------------' . PHP_EOL;

    foreach ($users as $id => $user) {
        echo implode(
            ' | ',
            array(
                $id,
                $user['userName'],
                $user['userRole'],
                implode(',', $user['channels'])
            )
        );

        if ($iterator > 0) {
            echo PHP_EOL;
            --$iterator;
        }
    }
}
