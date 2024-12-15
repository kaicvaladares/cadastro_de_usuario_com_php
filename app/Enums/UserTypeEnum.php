<?php declare(strict_types=1);

namespace App\Enums;
use BenSampo\Enum\Enum;

/**
 * Enum tipo de usuário
 *
 * @author Kaic Valadares <valadares19@gmail.com>
 * @since 14/12/2024
 * @version 1.0.0
 */
final class UserTypeEnum extends Enum {

    const WEB = 0;
    const API = 1;
}
