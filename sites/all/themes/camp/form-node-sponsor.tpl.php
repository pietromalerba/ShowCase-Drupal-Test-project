<div id="sponsor-node-form" class="clear-block">

    <p>Thank you for choosing to support LA Drupal and the Drupal Community as a whole.  Filling out the form below will help us automate the process of getting your information on the DrupalCamp website.  You may also edit this information (at any time) after your sponsorship is paid for.</p>
    <p><strong>Pay by Check:</strong> Fill out this form with your information and click save.  Send us an email at paypal@ladrupal.org to let us know that you'll be sending us money via check and when we can expect it.</p>
    <p><strong>PayPal:</strong> Fill out this form with your information and then browse your way over to <a href="http://paypal.com">PayPal</a> and send your sponsorship payment to paypal@ladrupal.org.</p>
    
    
    
    <div class="form-item-wrapper">
    <?php echo drupal_render($form['title']) ?>
    <?php echo drupal_render($form['field_sponsor_website']) ?>
    <?php echo drupal_render($form['taxonomy']) ?>
    </div>
    
     <div class="form-item-wrapper image-upload">
    <?php echo drupal_render($form['field_sponsor_image']) ?>
    </div>
    
     <div class="form-item-wrapper attendees-wrapper">
    <?php echo drupal_render($form['field_sponsor_attendees']) ?>
    </div>
    
     <div class="form-item-wrapper description-wrapper">
    <?php echo drupal_render($form['body_field']) ?>
    </div>
    <?php echo drupal_render($form); ?>
    <?php echo drupal_render($form['buttons']) ?>
</div>

