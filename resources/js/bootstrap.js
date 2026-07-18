/**
 * Front-end bootstrap.
 *
 * axios was removed: the app makes all AJAX calls with the native fetch() API
 * (wishlist toggle, live search), sending the CSRF token from the
 * <meta name="csrf-token"> tag, so bundling axios was dead weight (~13 kB gzip).
 * Add shared runtime setup here if needed.
 */
