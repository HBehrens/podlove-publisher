<?php
namespace Podlove\Settings;

/**
 * @todo The structure of this class differs from Format and Show.
 * That is because it is nested into Shows, not a standalone site.
 * It would be great to find a way to unify both structures.
 * You know, like Rails nested forms. But that might be overly complex.
 */
class Feed {
	
	protected $field_keys;
	protected $show;
	protected $feed;
	
	public function __construct( $hook, $feed ) {
		
		$raw_formats = \Podlove\Model\Format::all();
		$formats = array();
		foreach ( $raw_formats as $format ) {
			$formats[ $format->id ] = $format->name . ' (' . $format->extension . ')';
		}
		// todo: in brackets: extension
		
		$this->field_keys = array(
			'name' => array(
				'label'       => \Podlove\t( 'Internal Name' ),
				'description' => \Podlove\t( 'This is how this feed is presented to you within WordPress.' )
			),
			// todo: description --- can be used on the website to explain who should use this feed. optional.
			// todo: discovery name
			'discoverable' => array(
				'label'       => \Podlove\t( 'Discoverable?' ),
				'description' => \Podlove\t( 'Embed a meta tag into the head of your site so browsers and feed readers will find the link to the feed.' ),
				'args' => array(
					'type'     => 'checkbox'
				)
			),
			'title' => array(
				'label'       => \Podlove\t( 'Feed Title' ),
				'description' => \Podlove\t( 'This is how this feed is presented to users of podcast clients.' )
			),
			'slug' => array(
				'label'       => \Podlove\t( 'Slug' ),
				'description' => ( $feed ) ? sprintf( \Podlove\t( 'Feed URL: %s' ), $feed->get_subscribe_url() ) : ''
			),
			'suffix' => array(
				'label'       => \Podlove\t( 'File Suffix' ),
				'description' => \Podlove\t( 'Is appended to the media file.' )
			),			
			'format_id' => array(
				'label'       => \Podlove\t( 'File Format' ),
				'description' => \Podlove\t( '' ),
				'args' => array(
					'type'     => 'select',
					'options'  => $formats
				)
			),
			'itunes_feed_id' => array(
				'label'       => \Podlove\t( 'iTunes Feed ID' ),
				'description' => \Podlove\t( 'Is used to generate a link to the iTunes directory.' )
			),
			'language' => array(
				'label'       => \Podlove\t( 'Language' ),
				'description' => \Podlove\t( '' ),
				'args' => array(
					'default' => get_bloginfo( 'language' )
				)
			),
			// todo: select box with localized language names
			// todo: add PING url; see feedburner doc
			'redirect_url' => array(
				'label'       => \Podlove\t( 'Redirect Url' ),
				'description' => \Podlove\t( 'e.g. Feedburner URL' )
			),
			'block' => array(
				'label'       => \Podlove\t( 'Block feed?' ),
				'description' => \Podlove\t( 'Forbid podcast directories (e.g. iTunes) to list this feed.' ),
				'args' => array(
					'type'     => 'checkbox'
				)
			),
			'show_description' => array(
				'label'       => \Podlove\t( 'Include Description?' ),
				'description' => \Podlove\t( 'You may want to hide the episode descriptions to reduce the feed file size.' ),
				'args' => array(
					'type'     => 'checkbox'
				)
			),
			// todo include summary?
			'limit_items' => array(
				'label'       => \Podlove\t( 'Limit Items' ),
				'description' => \Podlove\t( 'A feed only displays the most recent episodes. Define the amount. Leave empty to use the WordPress default.' )
			)
			// todo: radio 1) wp default (show default) 2) custom 3) all 4) limit feed size (default = 512k = feedburner)
		);
		
		add_action( 'admin_init', array( $this, 'process_form' ) );
		
		if ( ! $feed )
			return;
		
		$this->feed = $feed;
		
		if ( isset( $_REQUEST[ 'show' ] ) ) {
			$show_id = (int) $_REQUEST[ 'show' ];
			$this->show = \Podlove\Model\Show::find_by_id( $show_id );
			add_meta_box(
				/* $id            */ 'podlove_show_feeds_' . $feed->id,
				/* $title         */ sprintf( '%s: %s', \Podlove\t( 'Feed' ), $feed->name ),
				/* $callback      */ array( $this, 'feed_box' ),
				/* $page          */ $hook,
				/* $context       */ 'normal',
				/* $priority      */ 'default'
				/* $callback_args */ 
			);
		}
	}
	
	/**
	 * Process form: save/update a show
	 */
	private function save() {
		$show_id = ( isset( $_REQUEST[ 'show' ] ) ) ? (int) $_REQUEST[ 'show' ] : NULL;
		$feed_id = ( isset( $_REQUEST[ 'feed' ] ) ) ? (int) $_REQUEST[ 'feed' ] : NULL;
		
		$feed = \Podlove\Model\Feed::find_by_id( $feed_id );
		
		if ( ! $show_id || ! $feed_id || $feed_id != $this->feed->id  )
			return;
			
		if ( ! isset( $_POST[ 'podlove_feed' ] ) || ! is_array( $_POST[ 'podlove_feed' ] ) )
			return;
		
		foreach ( $this->field_keys as $key => $values ) {
			if ( isset( $values[ 'args' ] ) && isset( $values[ 'args' ][ 'type' ] ) && $values[ 'args' ][ 'type' ] == 'checkbox' ) {
				$feed->{$key} = ( isset( $_POST[ 'podlove_feed' ][ $key ] ) && $_POST[ 'podlove_feed' ][ $key ] === 'on' ) ? 1 : 0;
			} else {
				$feed->{$key} = $_POST[ 'podlove_feed' ][ $key ];
			}
		}
		
		$feed->save();
		
		$this->redirect( 'edit', $show_id );
	}
	
	/**
	 * Process form: create new show
	 */
	private function create() {
		$show_id = ( isset( $_REQUEST[ 'show' ] ) ) ? (int) $_REQUEST[ 'show' ] : NULL;

		if ( ! $show_id )
			return;
			
		$feed = new \Podlove\Model\Feed;
		$feed->show_id = $show_id;
		$feed->save();
		$this->redirect( 'edit', $show_id );
	}
	
	/**
	 * Process form: delete a show
	 */
	private function delete() {
		$show_id = ( isset( $_REQUEST[ 'show' ] ) ) ? (int) $_REQUEST[ 'show' ] : NULL;
		$feed_id = ( isset( $_REQUEST[ 'feed' ] ) ) ? (int) $_REQUEST[ 'feed' ] : NULL;
		
		$feeds = \Podlove\Model\Feed::find_all_by_show_id( $show_id );
		
		foreach ( $feeds as $feed ) {
			if ( $feed->id == $feed_id ) {
				$feed->delete();
			}
		}
			
		$this->redirect( 'edit', $show_id );
	}
	
	/**
	 * Helper method: redirect to a certain page.
	 */
	private function redirect( $action, $show_id = NULL ) {
		$page   = 'admin.php?page=' . $_REQUEST[ 'page' ];
		$show   = ( $show_id ) ? '&show=' . $show_id : '';
		$action = '&action=' . $action;
		
		wp_redirect( admin_url( $page . $show . $action ) );
		exit;
	}
	
	public function process_form() {
		$action = ( isset( $_REQUEST[ 'action' ] ) ) ? $_REQUEST[ 'action' ] : NULL;
		if ( $action == 'save' ) {
			$this->save();
		} elseif ( $action == 'delete' ) {
			$this->delete();
		} elseif ( $action == 'create' ) {
			$this->create();
		}
	}
	
	public function page() {
	}
	
	private function new_template() {
	}
	
	private function view_template() {

	}
	
	public function feed_box( $_ ) {
		?>
		<form action="<?php echo admin_url( 'admin.php?page=' . $_REQUEST[ 'page' ] ) ?>" method="post">
			<input type="hidden" name="show" value="<?php echo $this->show->id ?>" />
			<input type="hidden" name="feed" value="<?php echo $this->feed->id ?>" />
			<input type="hidden" name="action" value="save" />
			<table class="form-table">
				<?php foreach ( $this->field_keys as $key => $value ): ?>
					<?php \Podlove\Form\input( 'podlove_feed', $this->feed->{$key}, $key, $value ); ?>
				<?php endforeach; ?>
			</table>
			
			<?php submit_button(); ?>
			<span class="delete">
				<a href="?page=<?php echo $_REQUEST[ 'page' ]; ?>&amp;action=delete&amp;show=<?php echo $this->show->id; ?>&amp;feed=<?php echo $this->feed->id; ?>" style="float: right" class="button-secondary delete">
					<?php echo \Podlove\t( 'Delete Feed' ); ?>
				</a>
			</span>
			<br class="clear" />
		</form>
		<?php
	}
	
	private function form_template( $show, $action, $button_text = NULL ) {

	}
	
	private function edit_template() {

	}
	
}
