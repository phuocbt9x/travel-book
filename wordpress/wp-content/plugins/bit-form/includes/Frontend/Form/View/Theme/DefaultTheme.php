<?php

namespace BitCode\BitForm\Frontend\Form\View\Theme;

final class DefaultTheme extends ThemeBase
{
  protected function fileUp($field, $rowID, $field_name, $formID, $error = null, $value = null)
  {
    $name = esc_attr($field_name);
    $name .= isset($field->mul) ? '[]' : null;
    $isDisabled = empty($field->valid->disabled) ? null : 'disabled';
    $upBtnTxt = isset($field->upBtnTxt) ? '<span>' . esc_html($field->upBtnTxt) . '</span>' : '';
    $maxUpload = isset($field->mxUp) ? 'Max ' . esc_html($field->mxUp) . ' MB' : '';
    $req = isset($field->req) ? 'required' : '';
    $mul = isset($field->mul) ? 'multiple' : '';
    $extention = isset($field->exts) ? "accept='" . esc_attr($field->exts) . "'" : '';
    return sprintf(
      '       <div class="btcd-f-input">
                <div class="btcd-f-wrp">
                  <div class="btn-wrp">
                    <button class="btcd-inpBtn" type="button">
                      <svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" width="15" height="15"><path d="M13.5 7.5l-5.757 5.757a4.243 4.243 0 01-6-6l5.929-5.929a2.828 2.828 0 014 4l-5.758 5.758a1.414 1.414 0 01-2-2L9.5 3.5" stroke="currentColor" /></svg>
                      %1$s
                    </button>
                  </div>
                  <span class="btcd-f-title">No File Chosen</span>
                  <small class="f-max">%2$s</small>
                  <input id="%3$s" name="%4$s" %5$s %6$s %7$s %8$s type="file" />
                </div>
              </div>',
      $upBtnTxt,
      $maxUpload,
      $rowID,
      $name,
      $req,
      $mul,
      $extention,
      $isDisabled
    );
  }

  protected function submitBtns($field, $style, $field_id)
  {
    ob_start(); ?>
    <div class="<?php echo esc_attr($style); ?>">
      <div>
        <div class="btcd-frm-sub <?php 'center' === $field->align ? $this->setSingleValuedAttribute('j-c-c') : ''; ?><?php 'right' === $field->align ? $this->setSingleValuedAttribute('j-c-e') : ''; ?>">
          <button class="btcd-sub-btn btcd-sub <?php 'md' === $field->btnSiz ? $this->setSingleValuedAttribute('btcd-btn-md') : ''; ?> <?php $field->fulW ? $this->setSingleValuedAttribute('ful-w') : ''; ?>" type="submit" <?php isset($field->name) ? $this->setAttribute('name', $field->name) : '' ?>><?php echo esc_html($field->subBtnTxt) ?></button>
          <?php //{'rstBtnTxt' in attr && <button className={`btcd-sub-btn btcd-rst ${attr.btnSiz === 'md' && 'btcd-btn-md'} ${attr.fulW && 'ful-w'}`} type="button">{attr.rstBtnTxt}</button>}
          ?>
          <?php if (!empty($field->rstBtnTxt)) : ?>
            <button class="btcd-sub-btn btcd-rst <?php 'md' === $field->btnSiz ? $this->setSingleValuedAttribute('btcd-btn-md') : ''; ?> <?php $field->fulW ? $this->setSingleValuedAttribute('ful-w') : ''; ?>" type="reset"><?php echo esc_html($field->rstBtnTxt) ?></button>
          <?php endif ?>
        </div>
      </div>
    </div>
<?php
    return ob_get_clean();
  }

  protected function button($field, $style, $field_name, $formID, $error, $value)
  {
    $isDisabled = empty($field->valid->disabled) ? null : 'disabled';
    $align = isset($field->align) ? ('center' === $field->align ? 'j-c-c' : ('right' === $field->align ? 'j-c-e' : '')) : '';
    if ('reset' === $field->btnTyp) {
      $btnCls = 'btcd-rst';
      $name = '';
    } else {
      $btnCls = 'btcd-sub';
      $name = " name='$field_name'";
    }
    $btnSizCls = 'md' === $field->btnSiz ? 'btcd-btn-md' : '';
    $btnfulW = !empty($field->fulW) ? 'ful-w' : '';
    $btnTyp = !empty($field->btnTyp) ? "type='$field->btnTyp'" : '';
    return sprintf(
      '<div class="btcd-frm-sub %1$s">
        <button
          class="btcd-sub-btn %2$s %3$s %4$s"
          %5$s
          %6$s
          %7$s
        >
          %8$s
        </button>
      </div>',
      $align,
      $btnCls,
      $btnSizCls,
      $btnfulW,
      $btnTyp,
      $name,
      $isDisabled,
      $field->txt
    );
  }

  protected function textField($field, $rowID, $field_name, $formID, $error = null, $value = null)
  {
    $isDisabled = empty($field->valid->disabled) ? null : 'disabled';
    $readonly = empty($field->valid->readonly) ? null : 'readonly';
    $name = isset($field_name) ? "name='" . esc_attr($field_name) . "'" : '';
    $ph = isset($field->ph) ? "placeholder='" . esc_attr($field->ph) . "'" : '';
    $mx = isset($field->mx) ? "max='" . esc_attr($field->mx) . "'" : '';
    $mn = isset($field->mn) ? "min='" . esc_attr($field->mn) . "'" : '';
    $val = isset($value) ? "value='" . esc_attr($value) . "'" : '';
    $required = isset($field->valid->req) ? 'required' : '';

    return sprintf(
      '<input id="%1$s" class="fld fld-%2$s no-drg" type="%3$s" %4$s %5$s %6$s %7$s %8$s %9$s %10$s %11$s/>',
      $rowID,
      $formID,
      $field->typ,
      $name,
      $ph,
      $mx,
      $mn,
      $val,
      $required,
      $isDisabled,
      $readonly
    );
  }

  protected function textArea($field, $rowID, $field_name, $formID, $error = null, $value = null)
  {
    $isDisabled = empty($field->valid->disabled) ? null : 'disabled';
    $readonly = empty($field->valid->readonly) ? null : 'readonly';
    $name = isset($field_name) ? "name='" . esc_attr($field_name) . "'" : '';
    if (isset($field->ph)) {
      $ph = htmlentities($field->ph, ENT_QUOTES);
    }
    $ph = isset($field->ph) ? "placeholder='$ph'" : '';
    $val = isset($value) ? $value : '';
    $required = isset($field->valid->req) ? 'required' : '';

    return sprintf(
      '<div>
      <textarea id="%1$s" class="fld fld-%2$s no-drg" type="%3$s" %4$s %5$s %6$s %7$s %8$s>%9$s</textArea>
    </div>',
      $rowID,
      $formID,
      $field->typ,
      $name,
      $ph,
      $required,
      $isDisabled,
      $readonly,
      $val
    );
  }

  protected function dropDown($field, $rowID, $field_name, $formID, $error = null, $value = null)
  {
    $defaultValue = null === $value ? [] : array_map('esc_html', $value);
    $isDisabled = empty($field->valid->disabled) ? (empty($field->valid->readonly) ? null : 'msl-disabled') : 'msl-disabled';
    $mul = isset($field->mul);
    $ph = isset($field->ph) ? "data-placeholder='$field->ph'" : "data-placeholder='Select...'";
    $val = '';
    if (isset($field->val)) {
      $dval = is_string($field->val) ? $field->val : implode(',', $field->val);
      $val = "value='$dval'";
    }
    $options = '';
    foreach ($field->opt as $selectOption) {
      $label = esc_html($selectOption->label);
      $value = esc_html($selectOption->value);
      //  preg_match('/asd 1/', $input_line, $output_array);
      $selected = in_array($value, $defaultValue) ? 'msl-option-selected' : '';
      $options .= "<option title='$label' class='msl-option vis-n  $selected' value='$value'>$label</option>";
    }

    $defaultValuePlacehold = "<div data-msl='true' $ph class='msl-input' contenteditable='true'></div>";
    if (null !== $defaultValue && 1 === sizeof($defaultValue) && !$mul) {
      $defaultValuePlacehold = "<span class='msl-single-value' data-msl='true'>$defaultValue[0]</span>";
    }
    return sprintf(
      '        <div class="msl-wrp msl-vars no-drg %1$s fld fld-%2$s dpd" style="width: 100%%;">
          <input name="%3$s" type="hidden" value="%4$s">
            <div data-msl="true" class="msl">
              <div data-msl="true" class="msl-input-wrp" tabindex="0">
                %5$s
              </div>
                <div class="msl-actions msl-flx">
                  <div role="button" aria-label="toggle-menu" class="msl-btn msl-arrow-btn msl-flx"></div>
                </div>
            </div>
            <div class="msl-options">
              %6$s
            </div>
          </div>',
      $isDisabled,
      $formID,
      $field_name,
      $val,
      $defaultValuePlacehold,
      $options
    );
  }

  protected function recaptcha($field, $rowID, $field_name, $formID, $error = null, $value = null)
  {
    return '<div class="btcd-flx j-c-c" style="min-height=inherit"></div>';
  }

  protected function paypal($field, $rowID, $field_name, $formID, $error = null, $value = null)
  {
    return ' <div style="width: auto; min-width: 150px; max-width: 750px; margin-left: auto; margin-right: auto;"></div>';
  }

  protected function razorPay($field, $rowID, $field_name, $formID, $error = null, $value = null)
  {
    $center = 'center' === $field->align ? 'j-c-c' : '';
    $right = 'right' === $field->align ? 'j-c-e' : '';
    $btnSiz = 'md' === $field->btnSiz ? 'btcd-btn-md' : '';
    $fulW = $field->fulW ? 'ful-w' : '';
    $btnTxt = isset($field->btnTxt) ? esc_html($field->btnTxt) : '';

    return sprintf(
      '<div class="btcd-frm-sub %1$s %2$s">
          <button class="btcd-sub-btn btcd-sub %3$s %4$s" type="button" name="%5$s">
            %6$s
          </button>
        </div>',
      $center,
      $right,
      $btnSiz,
      $fulW,
      $field_name,
      $btnTxt
    );
  }

  protected function checkBox($field, $rowID, $field_name, $formID, $error = null, $value = null)
  {
    $isDisabled = empty($field->valid->disabled) ? null : 'disabled';
    $readonly = empty($field->valid->readonly) ? null : 'readonly';
    $round = isset($field->round) ? 'btcd-round' : '';
    $options = '';

    foreach ($field->opt as $checkBoxOption) {
      $name = isset($field_name) ? "name='" . esc_attr($field_name) . '[]' . "'" : '';
      $required = isset($checkBoxOption->req) ? 'required' : '';
      $checked = isset($checkBoxOption->check) ? 'checked' : '';
      $checkBoxOptionValue = isset($checkBoxOption->val) ? esc_html($checkBoxOption->val) : esc_html($checkBoxOption->lbl);
      $value = null === $value ? '' : $value;
      if ((!is_array($value) && false !== strpos($value, $checkBoxOptionValue)) || (isset($value) && \is_array($value) && $checkBoxOptionValue === $value[array_search($checkBoxOptionValue, $value)])) {
        $checked = 'checked';
      }
      $options .= sprintf(
        '<label class="btcd-ck-wrp btcd-ck-wrp-%1$s">
           <span>%2$s</span>
           <input type="checkbox" %3$s %4$s %5$s value="%6$s" %7$s %8$s/>
           <span class="btcd-mrk ck"></span>
        </label>',
        $formID,
        $checkBoxOption->lbl,
        $checked,
        $required,
        $name,
        $checkBoxOptionValue,
        $isDisabled,
        $readonly
      );
    }

    return sprintf(
      '<div class="no-drg fld btcd-ck-con %1$s">
        %2$s
      </div>',
      $round,
      $options
    );
  }

  protected function radioBox($field, $rowID, $field_name, $formID, $error = null, $value = null)
  {
    $isDisabled = empty($field->valid->disabled) ? null : 'disabled';
    $readonly = empty($field->valid->readonly) ? null : 'readonly';
    $round = isset($field->round) ? 'btcd-round' : '';
    $options = '';

    foreach ($field->opt as $checkBoxOption) {
      $name = isset($field_name) ? "name='" . esc_attr($field_name) . "'" : '';
      $required = isset($checkBoxOption->req) ? 'required' : '';
      $optionValue = esc_html($checkBoxOption->lbl);
      $checked = '';
      if (isset($checkBoxOption->check) || $checkBoxOption->lbl === $value) {
        $checked = 'checked';
      }
      $options .= sprintf(
        '<label class="btcd-ck-wrp btcd-ck-wrp-%1$s">
          <span>%2$s</span>
          <input type="radio" %3$s %4$s %5$s value="%6$s" %7$s %8$s/>
          <span class="btcd-mrk rdo"></span>
        </label>',
        $formID,
        $checkBoxOption->lbl,
        $checked,
        $required,
        $name,
        $optionValue,
        $isDisabled,
        $readonly
      );
    }

    return sprintf(
      '<div class="no-drg fld btcd-ck-con %1$s">
        %2$s
      </div>',
      $round,
      $options
    );
  }

  protected function decisionBox($field, $rowID, $field_name, $formID, $error = null, $value = null)
  {
    $isRequired = !empty($field->valid->req) ? 'required' : '';
    $isChecked = !empty($field->valid->checked) ? 'checked' : '';
    $isDisabled = empty($field->valid->disabled) ? null : 'disabled';
    $readonly = empty($field->valid->readonly) ? null : 'readonly';
    $value = $isChecked ? $field->msg->checked : $field->msg->unchecked;
    $round = isset($field->round) ? 'btcd-round' : '';
    $size = $isRequired ? '1px' : '';

    $lbl = !empty($field->lbl) ? wp_kses_post($field->lbl) : (isset($field->info) ? wp_kses_post($field->info->lbl) : '');

    return sprintf(
      '<div class="no-drg fld fld-%1$s btcd-ck-con %2$s">
        <input
          size="height: %3$s, width: %3$s"
          type="checkbox"
          %4$s
          %5$s
          %6$s
          %7$s
          value="%8$s"
        />
        <label class="btcd-ck-wrp btcd-ck-wrp-%1$s">
          <span class="decision-content">
            %9$s
          </span>
          <input type="hidden" value="%8$s" name="%10$s" />
          <span class="btcd-mrk ck"></span>
        </label>
        </div>',
      $formID,
      $round,
      $size,
      $isDisabled,
      $readonly,
      $isRequired,
      $isChecked,
      $value,
      $lbl,
      $field_name
    );
  }

  protected function html($field, $rowID, $field_name, $formID, $error = null, $value = null)
  {
    $isHidden = !empty($field->valid->hide) && $field->valid->hide ? 'vis-n' : null;

    $content = !empty($field->content) ? wp_kses_post($field->content) : '';

    return sprintf(
      '<div class="btcd-fld-itm %1$s %2$s">
      <div class="fld-wrp fld-wrp-%3$s drag" btcd-fld="decision-box">
        %4$s
      </div>
    </div>',
      $rowID,
      $isHidden,
      $formID,
      $content
    );
  }
}
