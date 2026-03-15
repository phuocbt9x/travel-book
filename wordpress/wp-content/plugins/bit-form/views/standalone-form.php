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
  * {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
  }

  .standalone-form-container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .standalone-form-wrapper {
    width: 40%;
    /* Additional styling for the container, if needed */
  }

  @media (max-width: 575.98px) {
    .standalone-form-wrapper {
      width: 100%;
    }
  }

  ._frm-bg-b<?php echo esc_html($formID);

?> {
    width: 100%;
  }
  </style>
  <?php
  $formUpdateVersion = get_option('bit-form_form_update_version');
$baseCSSPath = "/form-styles/bitform-{$formID}.css";
$customCSSPath = "/form-styles/bitform-custom-{$formID}.css";
$standaloneCSSPath = "/form-styles/bitform-standalone-{$formID}.css";
?>
  <link rel="stylesheet" href="<?php echo esc_url(BITFORMS_UPLOAD_BASE_URL . $baseCSSPath . "?bfv={$formUpdateVersion}")?>" /> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- standalone HTML page without wp_head() ?>

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

<body>
  <div class="standalone-form-container">
    <div class="standalone-form-wrapper">
      <?php
  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $formHTML contains HTML output intended for rendering, escaped internally.
      echo $formHTML
?>
    </div>
  </div>

  <script>
  <?php
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $bfGlobals contains wp_json_encode() output intended for JS context and is safe to output.
    echo $bfGlobals;
?>
  <?php
  // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
  $previewJsPath = BITFORMS_UPLOAD_BASE_URL . '/form-scripts/preview-' . $formID . ".js?bfv={$formUpdateVersion}";
?>
  </script>
  <script src="<?php echo esc_url($previewJsPath) ?>"></script> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- standalone HTML page without wp_footer() ?>
  </div>
</body>

</html>