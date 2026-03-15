<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme\Fields;

use BitCode\BitForm\Core\Util\FrontendHelpers;

class FileUploadField
{
  public static function init($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $inputWrapper = new ClassicInputWrapper($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    $input = self::field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error, $value);
    return $inputWrapper->wrapper($input);
  }

  private static function field($field, $rowID, $field_name, $form_atomic_Cls_map, $formID, $error = null, $value = null)
  {
    $fh = new ClassicFieldHelpers($field, $rowID, $form_atomic_Cls_map);
    $prefixIcn = $fh->icon('prefixIcn', 'pre-i');
    $suffixIcn = $fh->icon('suffixIcn', 'suf-i');
    $name = $fh->name();
    $req = $fh->required();
    $readonlyCls = isset($field->readonly) ? 'readonly' : '';
    $disabledCls = isset($field->disabled) ? 'disabled' : '';
    $btnTxt = isset($field->btnTxt) ? $field->btnTxt : '';
    $showSelectStatus = '';
    $maxSizeSection = '';
    $maxSize = isset($field->config->maxSize) ? $field->config->maxSize : '';
    $sizeUnit = isset($field->config->sizeUnit) ? $field->config->sizeUnit : '';
    $bfFrontendFormIds = FrontendHelpers::$bfFrontendFormIds;
    $contentCount = count($bfFrontendFormIds);

    if ($fh->property_exists_nested($field, 'config->showSelectStatus', true)) {
      $showSelectStatus = sprintf(
        '<div
          %1$s
          class="%2$s %3$s"
        >
          No Choosen File
        </div>',
        $fh->getCustomAttributes('file-select-status'),
        $fh->getAtomicCls('file-select-status'),
        $fh->getCustomClasses('file-select-status')
      );
    }

    if (
      $fh->property_exists_nested($field, 'config->allowMaxSize', true)
      && $fh->property_exists_nested($field, 'config->showMaxSize', true)
      && $fh->property_exists_nested($field, 'config->maxSize')
      && 0 !== $field->config->maxSize
    ) {
      $maxSizeLbl = $fh->property_exists_nested($field, 'config->maxSizeLabel', '', 1) ? $field->config->maxSizeLabel : "(Max {$maxSize} {$sizeUnit})";
      $maxSizeSection = sprintf(
        '<small
          %1$s
          class="%2$s %3$s"
        >
          %4$s
        </small>',
        $fh->getCustomAttributes('max-size-lbl'),
        $fh->getAtomicCls('max-size-lbl'),
        $fh->getCustomClasses('max-size-lbl'),
        $fh->esc_html($maxSizeLbl)
      );
    }

    return sprintf(
      '<div
        %1$s
        class="%2$s %3$s"
      >
        <div
          %4$s
          class="%5$s %6$s %7$s %8$s"
        >
          <div
            %9$s
            class="%10$s %11$s"
          >
            <div
              %12$s
              class="%13$s %14$s"
            >
              <button
                %15$s
                type="button"
                class="%16$s %17$s"
              >
                %18$s
                <span
                  %19$s
                  class="%20$s %21$s"
                >
                  %22$s
                </span>
                %23$s
              </button>
              %24$s
              %25$s
              <input
                %26$s
                type="file"
                class="%27$s %28$s"
                id="%29$s-%30$s"
                %31$s
                %32$s
                %33$s
                %34$s
                aria-disabled="true"
                tabindex="-1"
              />
            </div>
            <div
              %35$s
              class="err-wrp %36$s"
            ></div>
          </div>
        </div>
      </div>',
      $fh->getCustomAttributes('file-up-container'),
      $fh->getAtomicCls('file-up-container'),
      $fh->getCustomClasses('file-up-container'),
      $fh->getCustomAttributes('file-up-wrpr'),
      $fh->getAtomicCls('file-up-wrpr'),
      $fh->getCustomClasses('file-up-wrpr'),
      $readonlyCls,
      $disabledCls,
      $fh->getCustomAttributes('file-input-wrpr'),
      $fh->getAtomicCls('file-input-wrpr'),
      $fh->getCustomClasses('file-input-wrpr'),
      $fh->getCustomAttributes('btn-wrpr'),
      $fh->getAtomicCls('btn-wrpr'),
      $fh->getCustomClasses('btn-wrpr'),
      $fh->getCustomAttributes('inp-btn'),
      $fh->getAtomicCls('inp-btn'),
      $fh->getCustomClasses('inp-btn'),
      $prefixIcn,
      $fh->getCustomAttributes('btn-txt'),
      $fh->getAtomicCls('btn-txt'),
      $fh->getCustomClasses('btn-txt'),
      $fh->kses_post($btnTxt),
      $suffixIcn,
      $showSelectStatus,
      $maxSizeSection,
      $fh->getCustomAttributes('file-upload-input'),
      $fh->getAtomicCls('file-upload-input'),
      $fh->getCustomClasses('file-upload-input'),
      $rowID,
      $contentCount,
      $name,
      $req,
      $fh->disabled(),
      $fh->readonly(),
      $fh->getCustomAttributes('err-wrp'),
      $fh->getCustomClasses('err-wrp')
    );
  }
}
