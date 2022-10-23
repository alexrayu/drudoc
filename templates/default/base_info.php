# <?php _d($data['yml']['info']['name']); ?>
<?php _d($data['yml']['info']['description']); ?>

## Basic Information:
- **Name:** <?php _d($data['yml']['info']['name']); ?>
- **Machine name:** `<?php echo $data['_pathinfo']['filename']; ?>`.
- **Package:** <?php _d($data['yml']['info']['package'] ?? 'Custom'); ?>
- **Core:** <?php _d($data['yml']['info']['core_version_requirement'] ?? '8'); ?>
<?php if (!empty($data['yml']['info']['dependencies'])) { ?>
- **Dependencies:**
  <?php foreach ($data['yml']['info']['dependencies'] as $dependency) { ?>
  - `<?php echo $dependency; ?>`.
  <?php } ?>
<?php } ?>

<?php if (!empty($data['yml']['services']['services'])) { ?>
## Services:
<?php $i = 0; ?>
<?php foreach ($data['yml']['services']['services'] as $id => $item) { ?>
<?php $i++; ?>
<?php echo $i; ?>. `<?php echo $id; ?>`.
- **Class:** `<?php echo urldecode($item['class']); ?>`.
<?php if (!empty($item['arguments'])) {
$args = [];
foreach ($item['arguments'] as $arg) {
  $args[] = '`' . $arg . "`\n";
} ?>
- **Arguments:** <?php echo implode('  - ', $args); ?>.
<?php } ?>
<?php if (!empty($item['tags'])) {
  $tags = [];
  foreach ($item['tags'] as $tag) {
    $tags[] = '`' . $tag['name'] . '`';
} ?>
- **Tags:** <?php echo implode(', ', $tags); ?>.
<?php } ?>
<?php } ?>
<?php } ?>

<?php if (!empty($data['yml']['routing'])) { ?>
## Routes:
<?php $i = 0; ?>
<?php foreach ($data['yml']['routing'] as $id => $item) { ?>
<?php
  $i++;
  if (!empty($item['defaults']['_controller'])) $handler = $item['defaults']['_controller'];
  elseif (!empty($item['defaults']['_form'])) $handler = $item['defaults']['_form'];
  else $handler = '*???*';
?>
<?php echo $i; ?>. `<?php echo $id; ?>`.
  - **Path:** `<?php echo urldecode($item['path']); ?>`.
  - **Handler:** `<?php echo urldecode($handler); ?>`.
<?php } ?>
<?php } ?>

<?php if (!empty($data['yml']['links.menu'])) { ?>
## Menu Links:
<?php foreach ($data['yml']['links.menu'] as $id => $item) { ?>
1. `<?php echo $id; ?>`: <?php echo $item['title']; ?>.
<?php } ?>
<?php } ?>

<?php if (!empty($data['yml']['libraries'])) { ?>
## Libraries:
<?php foreach ($data['yml']['libraries'] as $id => $item) { ?>
<?php
$types = [];
if (!empty($item['css'])) $types[] = 'css';
if (!empty($item['js'])) $types[] = 'js';
if (empty($types)) $types[] = '*Library is empty*';
?>
1. `<?php echo $id; ?>`: <?php echo implode(', ', $types); ?>.
<?php } ?>
<?php } ?>



<?php
/**
 * Print with dot.
 * @param string $string
 *   String.
 *
 * @return void
 */
function _d($string) {
  if ($string[strlen($string) - 1] !== '.') {
    $string .= ".\n";
  }
  echo $string;
}
?>
