<?php
// $Id: node.tpl.php,v 1.4 2008/01/25 21:21:44 goba Exp $

/**
 * @file node.tpl.php
 *
 * Theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: Node body or teaser depending on $teaser flag.
 * - $picture: The authors picture of the node output from
 *   theme_user_picture().
 * - $date: Formatted creation date (use $created to reformat with
 *   format_date()).
 * - $links: Themed links like "Read more", "Add new comment", etc. output
 *   from theme_links().
 * - $name: Themed username of node author output from theme_user().
 * - $node_url: Direct url of the current node.
 * - $terms: the themed list of taxonomy term links output from theme_links().
 * - $submitted: themed submission information output from
 *   theme_node_submitted().
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $teaser: Flag for the teaser state.
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 */
?>
<!-- <div class="sponsor-cta"><?php echo l(t('Become a Sponsor'), 'sponsorship') ?></div> -->
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"><div class="node-inner">

  <div class="content clear-block">

    <div class="top clear-block">
      <div class="sponsor-info">
        <h3 class="sponsor-level"><?php echo $sponsor_level ?></h3>

        <div class="sponsor-links">
          <?php echo $node->field_sponsor_image[0]['view'] ?><br />
          <?php echo $node->field_sponsor_website[0]['view'] ?>
        </div>
      </div>
      <div class="attendees">
        <h3>Attendees</h3>
        <div class="clear-block">
        <?php foreach ($node->field_sponsor_attendees as $attendee): ?>
          <div class="attendee"><?php echo $attendee['view'] ?></div>
        <?php endforeach ?>
        </div>
      </div>
    </div>
    
    <div class="body">
      <h3>About</h3>
      <?php echo $node->content['body']['#value'] ?>
    </div>
    
  </div>

</div></div>

