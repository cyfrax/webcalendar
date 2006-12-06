<?php
/* $Id$ */
include_once 'includes/init.php';

$error = '';

if ( $ALLOW_VIEW_OTHER != 'Y' )
  $error = print_not_auth ();

if ( empty ( $dups ) )
  $dups = 'N';

$updating_public = false;
if ( $is_admin && ! empty ( $public ) && $PUBLIC_ACCESS == 'Y' ) {
  $updating_public = true;
  save_layer ( '__public__', $layeruser, $layercolor, $dups, $id );
} else
if ( empty ( $cal_login ) )
  save_layer ( $login, $layeruser, $layercolor, $dups, $id );
else {
  // See if we are processing multiple layer_users as admin.
  if ( $is_admin && ! empty ( $cal_login ) ) {
    for ( $i = 0, $cnt = count ( $cal_login ); $i < $cnt; $i++ ) {
      save_layer ( $cal_login[$i], $layeruser, $layercolor, 'N', $id );
    }
  }
}

function save_layer ( $layer_user, $layeruser, $layercolor, $dups, $id ) {
  global $error, $layers;
  if ( $layer_user == $layeruser )
    $error = translate ( 'You cannot create a layer for yourself' ) . '.';

  load_user_layers ( $layer_user, 1 );

  if ( ! empty ( $layeruser ) && $error == '' ) {
    // existing layer entry
    if ( ! empty ( $layers[$id]['cal_layeruser'] ) ) {
      // Update existing layer entry for this user.
      $layerid = $layers[$id]['cal_layerid'];

      dbi_execute ( 'UPDATE webcal_user_layers SET cal_layeruser = ?,
        cal_color = ?, cal_dups = ? WHERE cal_layerid = ?',
        array ( $layeruser, $layercolor, $dups, $layerid ) );
    } else {
      // new layer entry
      // Check for existing layer for user. Can only have one layer per user.
      $res = dbi_execute ( 'SELECT COUNT(cal_layerid) FROM webcal_user_layers
        WHERE cal_login = ? AND cal_layeruser = ?',
        array ( $layer_user, $layeruser ) );
      if ( $res ) {
        $row = dbi_fetch_row ( $res );
        if ( $row[0] > 0 )
          $error = translate ( 'You can only create one layer for each user' )
           . '.';

        dbi_free_result ( $res );
      }
      if ( $error == '' ) {
        $res =
        dbi_execute ( 'SELECT MAX( cal_layerid ) FROM webcal_user_layers' );
        if ( $res ) {
          $row = dbi_fetch_row ( $res );
          $layerid = $row[0] + 1;
        } else
          $layerid = 1;

        dbi_execute ( 'INSERT INTO webcal_user_layers ( cal_layerid, cal_login,
          cal_layeruser, cal_color, cal_dups ) VALUES ( ?, ?, ?, ?, ? )',
          array ( $layerid, $layer_user, $layeruser, $layercolor, $dups ) );
      }
    }
  }
}
// .
// We don't want to throw error if doing a multiple save.
if ( $error == '' || ! empty ( $cal_login ) ) {
  do_redirect ( 'layers.php' . ( $updating_public ? '?public=1' : '' ) );
  exit;
}

print_header ();
echo print_error ( $error ) . print_trailer ();

?>
