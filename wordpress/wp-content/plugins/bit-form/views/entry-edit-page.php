<?php
if (!defined('ABSPATH')) {
  exit;
}
if (!defined('BITFORMS_ASSET_URI')) {
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($title) ? esc_html($title) : ''; ?></title>
  <style>
  html,
  body {
    min-height: 100%;
  }

  body {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    /* background-color: #f1f1f1; */
  }

  ._frm-bg-b<?php echo esc_html($formID) ?> {
    width: 600px;
    margin-block: 100px;
  }
  </style>
  <?php
  // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
  $formUpdateVersion = get_option('bit-form_form_update_version');
$bitformCssUrl = BITFORMS_UPLOAD_BASE_URL . '/form-styles/bitform-' . $formID . '.css?bfv=' . $formUpdateVersion;
?>
  <link rel="stylesheet" href="<?php echo esc_url($bitformCssUrl) ?>" /> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- standalone HTML page without wp_head() ?>
  <?php
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$customCssSubPath = "/form-styles/bitform-custom-{$formID}.css";

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$customJsPath = BITFORMS_UPLOAD_BASE_URL . $customCssSubPath . '?ver=' . $formUpdateVersion;
?>
  <?php if (file_exists(BITFORMS_CONTENT_DIR . $customCssSubPath)) : ?>
  <link rel="stylesheet" href="<?php echo esc_url($customJsPath) ?>" /> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- standalone HTML page without wp_head() ?>
  <?php endif; ?>

  <?php if (isset($font) && '' !== $font): ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="<?php echo esc_url($font)?>" /> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- standalone HTML page without wp_head() ?>
  <?php endif; ?>

</head>

<body>
  <?php
  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $formHTML contains HTML output intended for rendering, escaped internally.
  echo $formHTML
?>

  <script>
  <?php
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $bfGlobals contains wp_json_encode() output intended for JS context and is safe to output.
echo $bfGlobals;
?>
  <?php
  // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
  $previewJsPath = BITFORMS_UPLOAD_BASE_URL . '/form-scripts/preview-' . $formID . '.js';
?>
  </script>
  <script src="<?php echo esc_url($previewJsPath) ?>"></script> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- standalone HTML page without wp_footer() ?>

</body>

</html>