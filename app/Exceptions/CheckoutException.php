<?php

namespace App\Exceptions;

use Exception;

/**
 * A customer-safe checkout failure (e.g. out of stock, invalid variant).
 * Its message is intended for display; all other Throwables during checkout
 * are logged and shown a generic message instead.
 */
class CheckoutException extends Exception
{
}
