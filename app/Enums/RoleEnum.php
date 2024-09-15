<?php

namespace App\Enums;

enum RoleEnum: string
{
    case BOUTIQUE = 'Boutiquier';
    case ADMIN = 'Admin';
    case CLIENT = 'Client';
}
