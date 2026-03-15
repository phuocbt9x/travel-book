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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($title) ? esc_html($title) : 'Conversational Form'; ?></title>
  <style>
  * {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
  }

  body.bit-conversational-form {
    height: 100vh;
    overflow: hidden;
  }

  @media (max-width: 575.98px) {
    .standalone-form-wrapper {
      width: 100%;
    }
  }
  </style>
  <?php
    $formUpdateVersion = get_option('bit-form_form_update_version');
    $baseCSSPath = "/form-styles/bitform-{$formID}.css";
    $baseConversationalCSSPath = "/form-styles/bitform-conversational-{$formID}.css";
    $customCSSPath = "/form-styles/bitform-custom-{$formID}.css";
    $standaloneCSSPath = "/form-styles/bitform-standalone-{$formID}.css";
  ?>
  <link rel="stylesheet" href="<?php echo esc_url(BITFORMS_UPLOAD_BASE_URL . $baseCSSPath . "?bfv={$formUpdateVersion}") ?>" /> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- standalone HTML page without wp_head() ?>
  <link rel="stylesheet" href="<?php echo esc_url(BITFORMS_UPLOAD_BASE_URL . $baseConversationalCSSPath . "?bfv={$formUpdateVersion}") ?>" /> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- standalone HTML page without wp_head() ?>

  <?php if (file_exists(BITFORMS_CONTENT_DIR . $customCSSPath)) : ?>
  <link rel="stylesheet" href="<?php echo esc_url(BITFORMS_UPLOAD_BASE_URL . $customCSSPath . "?bfv={$formUpdateVersion}") ?>" /> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- standalone HTML page without wp_head() ?>
  <?php endif; ?>

  <?php if (file_exists(BITFORMS_CONTENT_DIR . $standaloneCSSPath)) : ?>
  <link rel="stylesheet" href="<?php echo esc_url(BITFORMS_UPLOAD_BASE_URL . $standaloneCSSPath . "?bfv={$formUpdateVersion}") ?>" /> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- standalone HTML page without wp_head() ?>
  <?php endif; ?>

  <?php if (isset($font) && '' !== $font) : ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="<?php echo esc_url($font) ?>" /> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- standalone HTML page without wp_head() ?>
  <?php endif; ?>

</head>

<body class="bit-conversational-form">
  <?php
  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $formHTML contains HTML output intended for rendering, escaped internally.
  echo $formHTML?>
  <script>
  <?php
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $bfGlobals contains wp_json_encode() output intended for JS context and is safe to output.
echo $bfGlobals;
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$jsPath = BITFORMS_UPLOAD_BASE_URL . '/form-scripts/bitform-conversational-' . $formID . ".js?bfv={$formUpdateVersion}"
?>;
  </script>
  <script src="<?php echo esc_url($jsPath) ?>"></script> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- standalone HTML page without wp_footer() ?>
</body>

</html>