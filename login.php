<?PHP
session_start();

// ── CSRF: generar token si no existe en sesión ──────────────────────────────
if (empty($_SESSION['csrf_token_login'])) {
	$_SESSION['csrf_token_login'] = bin2hex(random_bytes(32));
}
include("login_form.php");
?>