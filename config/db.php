<?php

Yii::setAlias('@db', dirname(__DIR__) . '/db');

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlite:'.Yii::getAlias('@db/db.sqlite'),
];
