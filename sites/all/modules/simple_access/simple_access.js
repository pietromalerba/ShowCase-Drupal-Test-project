Drupal.verticalTabs = Drupal.verticalTabs || {};

Drupal.verticalTabs.sa = function() {
  if (!$('.vertical-tabs-sa .form-checkbox:checked').size()) {
    return Drupal.t('Public access');
  }
  else {
    return Drupal.t('Restricted access');
  }
}