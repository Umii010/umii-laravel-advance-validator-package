
<?php

return [
    'strong_password' => [
        'min_length' => 8,
        // Regex class for "special characters" displayed as a raw string to avoid escaping confusion.
        'special_class' => '[^a-zA-Z\d]',
    ],
];
