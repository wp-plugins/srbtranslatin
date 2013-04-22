<?php


class SrbTransLatin_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'stl_scripts_widget', // Base ID
			'Serbian Script Selector', // Name
			array( 'description' => __( 'SrbTransLatin Script Selector', 'srbtranslatin' ), ) // Args
		);
	}


	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$show_title = $instance['show_title'];
		$selection_type = $instance['selection_type'];
		$oneline_separator = $instance['oneline_separator'];

		$cirilica_title = $instance['cirilica_title'];
		
		if (empty ($cirilica_title)) {
			$cirilica_title = 'ћирилица';
		}
		
		$latinica_title = $instance['latinica_title'];		
		
		if (empty ($latinica_title)) {
			$latinica_title = 'latinica';
		}
		

		echo '<span class="stl_widget">';
		echo $before_widget;
		if ( $show_title == 'on' )
			echo $before_title . $title . $after_title;

?>
<?php

		global $stl_default_language;

		if (isset ($_REQUEST['lang'])) {
			$m_current_language = $_REQUEST['lang'];
		} else {
			$m_current_language = $stl_default_language;
		}

//		if ($stl_default_language != 'cir') {
			$m_cir_url = url_current_add_param ('lang=cir', true);
//		} else {
//			$m_cir_url = url_current_clean_param('lang');
//		}


//		if ($stl_default_language != 'lat') {
			$m_lat_url = url_current_add_param ('lang=lat', true);
//		} else {
//			$m_lat_url = url_current_clean_param('lang');
//		}

		switch ($selection_type) {
			case 'list':


?>
<form action="" method="post">
<select name="lang" id="lang" onchange="this.form.submit()">
<option value="cir" <?php echo $m_current_language=='cir' ? 'selected="selected"' : '' ?>>[lang id="skip"]<?php echo $cirilica_title; ?>[/lang]</option>
<option value="lat" <?php echo $m_current_language=='lat' ? 'selected="selected"' : '' ?>><?php echo $latinica_title; ?></option>
</select>
</form>
<?php
				break;

			case 'oneline':

?>
<p>
<a href="<?php echo $m_cir_url; ?>">[lang id="skip"]<?php echo $cirilica_title; ?>[/lang]</a><?php echo $oneline_separator; ?><a href="<?php echo $m_lat_url; ?>"><?php echo $latinica_title; ?></a>
</p>
<?php

				break;



			default:


?>
<ul>
<li><a href="<?php echo $m_cir_url; ?>">[lang id="skip"]<?php echo $cirilica_title; ?>[/lang]</a></li>
<li><a href="<?php echo $m_lat_url; ?>"><?php echo $latinica_title; ?></a></li>
</ul>

<?php
	
	  } // switch

		echo $after_widget;
		echo '</span>';
	}


	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['show_title'] = strip_tags( $new_instance['show_title'] );
		$instance['selection_type'] = strip_tags( $new_instance['selection_type'] );
		$instance['oneline_separator'] = strip_tags( $new_instance['oneline_separator'] );
		$instance['cirilica_title'] = strip_tags( $new_instance['cirilica_title'] );
		$instance['latinica_title'] = strip_tags( $new_instance['latinica_title'] );		

		return $instance;
	}


	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __("Script selection", 'srbtranslatin');
		}

		if ( isset( $instance[ 'show_title' ] ) ) {
			$show_title = $instance[ 'show_title' ];
		}
		else {
			$show_title = 'on';
		}


		if ( isset( $instance[ 'selection_type' ] ) ) {
			$selection_type = $instance[ 'selection_type' ];
		}
		else {
			$selection_type = 'links';
		}


		if ( isset( $instance[ 'oneline_separator' ] ) ) {
			$oneline_separator = $instance[ 'oneline_separator' ];
		}
		else {
			$oneline_separator = ' | ';
		}


		if ( isset( $instance[ 'cirilica_title' ] ) ) {
			$cirilica_title = $instance[ 'cirilica_title' ];
		}
		else {
			$cirilica_title = 'ћирилица';
		}

		if ( isset( $instance[ 'latinica_title' ] ) ) {
			$latinica_title = $instance[ 'latinica_title' ];
		}
		else {
			$latinica_title = 'latinica';
		}

		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
		<input id="<?php echo $this->get_field_id( 'show_title' ); ?>" name="<?php echo $this->get_field_name( 'show_title' ); ?>" type="checkbox" <?php echo $show_title=='on' ? 'checked="checked"' : '' ?>> <?php _e("show widget title", 'srbtranslatin'); ?>
		</p>


		<p>
		<label for="<?php echo $this->get_field_id( 'cirilica_title' ); ?>"><?php _e( 'Cyril option title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'cirilica_title' ); ?>" name="<?php echo $this->get_field_name( 'cirilica_title' ); ?>" type="text" value="<?php echo esc_attr( $cirilica_title ); ?>" />
		</p>


		<p>
		<label for="<?php echo $this->get_field_id( 'latinica_title' ); ?>"><?php _e( 'Latin option title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'latinica_title' ); ?>" name="<?php echo $this->get_field_name( 'latinica_title' ); ?>" type="text" value="<?php echo esc_attr( $latinica_title ); ?>" />
		</p>
		

		<label for="<?php echo $this->get_field_id( 'selection_type' ); ?>"><?php _e( 'Selection type:' ); ?></label> 
		<select id="<?php echo $this->get_field_id( 'selection_type' ); ?>" name="<?php echo $this->get_field_name( 'selection_type' ); ?>">
			<option value="links" <?php echo $selection_type=='links' ? 'selected="selected"' : '' ?>><?php _e("web links", 'srbtranslatin')?></option>
			<option value="list" <?php echo $selection_type=='list' ? 'selected="selected"' : '' ?>><?php _e("combo box", 'srbtranslatin')?></option>
			<option value="oneline" <?php echo $selection_type=='oneline' ? 'selected="selected"' : '' ?>><?php _e("one line", 'srbtranslatin')?></option>
		</select>


		<p>
		<label for="<?php echo $this->get_field_id( 'oneline_separator' ); ?>"><?php _e( 'One line separator:', 'srbtranslatin' ); ?></label> 
		<input class="" size="4" id="<?php echo $this->get_field_id( 'oneline_separator' ); ?>" name="<?php echo $this->get_field_name( 'oneline_separator' ); ?>" type="text" value="<?php echo esc_attr( $oneline_separator ); ?>" />
		</p>


		<?php 
	}

}

?>