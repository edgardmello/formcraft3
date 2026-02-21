<?php
defined( 'ABSPATH' ) or die( 'Cheating, huh?' );
global $fc_meta, $wpdb, $fc_forms_table, $fc_progress_table, $fc_addons, $fc_addons_directory, $fc_triggers;

$form_id = intval($_GET['id']);
$query = $wpdb->prepare("SELECT name FROM $fc_forms_table WHERE id = %d", $form_id);
$form_name = stripslashes($wpdb->get_var( $query ));

$query = $wpdb->prepare("SELECT counter FROM $fc_forms_table WHERE id = %d", $form_id);
$counter = $wpdb->get_var($query);

$color_scheme = array(
	'4488ee',
	'4682B4',
	'a9a9a9',
	'e9967a',
	'3cb371',
	'8FBC8F',
	'F08080',
	'778899',
	'FF6347',
	'5F9EA0',
	'deb887',
	'ff69b4',
	'cd5c5c',
	'637bb3'
);

if (is_rtl()) {
	?>
	<script>
		window.isRTL = true;
	</script>
	<?php
} else {
	?>
	<script>
		window.isRTL = false;
	</script>
	<?php
}

$backgrounds = array();
$base = plugins_url( '../assets/images/backgrounds/', __FILE__ );
$backgrounds[] = array('Birds','url('.$base.'birds.jpg)','url('.$base.'thumb-birds.jpg)');
$backgrounds[] = array('Speck','url('.$base.'brillant.png)','url('.$base.'thumb-brillant.png)');
$backgrounds[] = array('Fibre','url('.$base.'fibre.png)','url('.$base.'thumb-fibre.png)');
$backgrounds[] = array('Noise','url('.$base.'grey-noise.png)','url('.$base.'thumb-grey-noise.png)');
$backgrounds[] = array('Lined','url('.$base.'lined.png)','url('.$base.'thumb-lined.png)');
$backgrounds[] = array('Linen','url('.$base.'white-linen.png)','url('.$base.'thumb-white-linen.png)');
$backgrounds[] = array('Coarse','url('.$base.'coarse.png)','url('.$base.'thumb-coarse.png)');
$backgrounds[] = array('Jeans','url('.$base.'jeans.png)','url('.$base.'jeans.png)');

?>
<?php wp_nonce_field('formcraft3_wpnonce', 'formcraft3_wpnonce', true, true); ?>
<input type='hidden' id='form_id' value='<?php echo $form_id; ?>'>
<div ng-app='FormCraft'>
	<div id='formcraft-builder-cover' class='formcraft-css form-loading' ng-controller='FormController' ng-init='AngularInit()'>
		<div class='main-loader formcraft-loader'></div>
		<div id='notification-panel'>
		</div>
		<div id='add-fields-panel' class='fields-list-right fields-list-sortable show-{{Builder.Config.showAddField}}'>
			<button class='toggleAddField' ng-click='Builder.Config.showAddField = !Builder.Config.showAddField'>
				<?php esc_html_e('Add Field','formcraft') ?>
				<i class='down-arrow formcraft-icon'>keyboard_arrow_down</i>
				<i class='up-arrow formcraft-icon'>keyboard_arrow_up</i>
			</button>
			<button draggable='true' ng-click='addFormElement("heading")'><?php esc_html_e('Heading','formcraft') ?></button>
			<button draggable='true' ng-click='addFormElement("oneLineText")'><?php esc_html_e('One Line Input','formcraft') ?></button>
			<button draggable='true' ng-click='addFormElement("email")'><?php esc_html_e('Email Input','formcraft') ?></button>
			<button draggable='true' ng-click='addFormElement("textarea")'><?php esc_html_e('Textarea','formcraft') ?></button>
			<button draggable='true' ng-click='addFormElement("checkbox")'><?php esc_html_e('Checkbox','formcraft') ?></button>
			<button draggable='true' ng-click='addFormElement("dropdown")'><?php esc_html_e('Dropdown','formcraft') ?></button>
			<button draggable='true' ng-click='addFormElement("datepicker")'><?php esc_html_e('Datepicker','formcraft') ?></button>
			<button draggable='true' ng-click='addFormElement("customText")'><?php esc_html_e('Custom Text','formcraft') ?></button>
			<button draggable='true' ng-click='addFormElement("submit")'><?php esc_html_e('Submit','formcraft') ?></button>
			<?php
			do_action('formcraft_after_fields');
			?>
			<div class='hover-on-fields'>
				<button><i class='formcraft-icon'>keyboard_arrow_left</i><?php esc_html_e('More Fields','formcraft') ?></button>
				<ul class='fields-list fields-list-sortable'>
					<li><button draggable='true' ng-click='addFormElement("password")'><?php esc_html_e('Password','formcraft') ?></button></li>
					<li><button draggable='true' ng-click='addFormElement("fileupload")'><?php esc_html_e('File Upload','formcraft') ?></button></li>
					<li><button draggable='true' ng-click='addFormElement("slider")'><?php esc_html_e('Slider','formcraft') ?></button></li>
					<li><button draggable='true' ng-click='addFormElement("timepicker")'><?php esc_html_e('Timepicker','formcraft') ?></button></li>
					<li><button draggable='true' ng-click='addFormElement("address")'><?php esc_html_e('Address','formcraft') ?></button></li>
					<li ng-repeat='field in addField.others track by $index'><button draggable='true' ng-click='addFormElement(field.name)'>{{field.name}}</button></li>
				</ul>
			</div>
			<div class='hover-on-fields'>
				<button><i class='formcraft-icon'>keyboard_arrow_left</i><?php esc_html_e('Survey','formcraft') ?></button>
				<ul class='fields-list fields-list-sortable'>
					<li><button draggable='true' ng-click='addFormElement("thumb")'><?php esc_html_e('Thumb Rating','formcraft') ?></button></li>
					<li><button draggable='true' ng-click='addFormElement("star")'><?php esc_html_e('Star Rating','formcraft') ?></button></li>
					<li><button draggable='true' ng-click='addFormElement("matrix")'><?php esc_html_e('Choice Matrix','formcraft') ?></button></li>
				</ul>
			</div>
			<div class='hover-on-fields fields-nos-{{addField.payments.length}}'>
				<button><i class='formcraft-icon'>keyboard_arrow_left</i>Payments</button>
				<ul class='fields-list fields-list-sortable'>
					<li ng-repeat='field in addField.payments track by $index'><button draggable='true' ng-click='addFormElement(field.name)'>{{field.name}}</button></li>
				</ul>
			</div>
		</div>

		<div class='option-box  state-{{Builder.Config.showLogic}}' id='form_logic_box'>
			<div id='logic_tabs' class='nav-content-slide'>
				<div class='active'>
					<div id='add-logic-heads'>
						<div><?php esc_html_e('Conditions', 'formcraft'); ?></div>
						<div><?php esc_html_e('Actions','formcraft'); ?></div>
					</div>
					<div class='add-logic-area' ng-repeat='logic in Builder.Config.Logic track by $index'>
						<div class='logic-text logic-text-if'>
							<?php esc_html_e('if','formcraft'); ?>
						</div>
						<div class='width-45 group actions-nos-{{Builder.Config.Logic[$index][0].length}}'>
							<div ng-repeat='action in Builder.Config.Logic[$index][0] track by $index' class='group-row show-{{Builder.Config.Logic[$parent.$index][0].length}}'>
								<div class='width-30'>
									<select id='select_fix_{{$parent.$index}}_{{$index}}' ng-model='action[0]'>
										<option value=''>(<?php esc_html_e('field','formcraft'); ?>)</option>
										<optgroup ng-repeat='page in Builder.FormElements track by $index' label='{{Builder.Config.page_names[$index]}}'>
											<option ng-repeat='element in page track by $index' value='{{element.identifier}}'>{{element.elementDefaults.main_label}}</option>
										</optgroup>
									</select>
								</div>
								<div class='width-33'>
									<select ng-model='action[1]'>
										<option value=''>(<?php esc_html_e('trigger','formcraft'); ?>)</option>
										<option value='equal_to'><?php esc_html_e('is equal to','formcraft'); ?></option>
										<option value='not_equal_to'><?php esc_html_e('is not equal to','formcraft'); ?></option>
										<option value='contains'><?php esc_html_e('contains','formcraft'); ?></option>
										<option value='contains_not'><?php esc_html_e('does not contain','formcraft'); ?></option>
										<option value='greater_than'><?php esc_html_e('is greater than','formcraft'); ?></option>
										<option value='less_than'><?php esc_html_e('is less than','formcraft'); ?></option>
									</select>
								</div>
								<div class='width-30'>
									<input type='text' ng-model='action[2]' placeholder='...'>
								</div>
								<div ng-click='removeLogicAction($parent.$index, $index)' class='remove-action'>
									×
								</div>
								<div class='and-or'>
									<select ng-model='Builder.Config.Logic[$parent.$index][2]'>
										<option value='and'><?php esc_html_e('And','formcraft'); ?></option>
										<option value='or'><?php esc_html_e('Or','formcraft'); ?></option>
									</select>
								</div>
							</div>
							<span ng-click='addLogicAction($index)' class='add-group'><?php esc_html_e('add condition row','formcraft'); ?></span>
						</div>
						<div class='logic-text logic-text-then'>
							<?php esc_html_e('then','formcraft'); ?>
						</div>
						<div class='width-40 group'>
							<div ng-repeat='result in Builder.Config.Logic[$index][1] track by $index' class='group-row'>
								<div class='width-100 sign-and'>
									&
								</div>								
								<div class='width-43 set-value-{{result[0]}}'>
									<select ng-model='result[0]'>
										<option value=''><?php esc_html_e('(action)','formcraft'); ?></option>
										<option value='show_fields'><?php esc_html_e('show fields','formcraft'); ?></option>
										<option value='hide_fields'><?php esc_html_e('hide fields','formcraft'); ?></option>
										<option value='email_to'><?php esc_html_e('send email to','formcraft'); ?></option>
										<option value='redirect_to'><?php esc_html_e('redirect to','formcraft'); ?></option>
										<option value='trigger_integration'><?php esc_html_e('trigger integration','formcraft'); ?></option>
										<option value='set_value'><?php esc_html_e('set value of','formcraft'); ?></option>
									</select>
									<select class='set-value-field' id='cons_select_fix_{{$parent.$index}}_{{$index}}' ng-model='result[4]'>
										<option value=''>(<?php esc_html_e('field','formcraft'); ?>)</option>
										<optgroup ng-repeat='page in Builder.FormElements track by $index' label='{{Builder.Config.page_names[$index]}}'>
											<option ng-repeat='element in page track by $index' value='{{element.identifier}}'>{{element.elementDefaults.main_label}}</option>
										</optgroup>
									</select>
								</div>
								<div class='result-type result-type-{{result[0]}}'>
									<input type='text' class='select-fields-logic' select-fields ng-model='result[1]' placeholder='(add fields)'/>
									<input type='text' class='type-in-logic' ng-model='result[2]' placeholder='...'>
									<?php
									if ( isset($fc_triggers) && count($fc_triggers)>0 ) {
										echo "<select class='trigger-intergration-select' ng-model='result[3]'>";
										echo "<option value=''>".esc_html__('(select)','formcraft')."</option>";
										foreach ($fc_triggers as $key => $value) {
											$value = esc_html__($value);
											echo "<option value='$value'>$value</option>";
										}
										echo "</select>";
									}
									?>
								</div>
								<div ng-click='removeLogicResult($parent.$index, $index)' class='remove-action'>
									×
								</div>
							</div>
							<div ng-click='addLogicResult($index)' class='add-group'><?php esc_html_e('add action row','formcraft'); ?></div>
						</div>
						<div class='remove-logic' ng-click='removeLogic($index)'>×</div>
					</div>
					<div id='add-logic-cover'>
						<button class='add-logic-button' ng-click='addLogic()'><?php esc_html_e('Add New Logic','formcraft'); ?></button>
						<a class='trigger-help' data-post-id='9'><?php esc_html_e('how to use Conditional Logic', 'formcraft'); ?></a>
					</div>
				</div>
			</div>
		</div>

		<div class='option-box state-{{Builder.Config.showAddons}}' id='form_addon_box'>
			<nav class='nav-tabs-slide' data-content='#addon_tabs'>
				<span class='active'><?php esc_html_e('Installed','formcraft'); ?></span>
				<span><?php esc_html_e('Add New','formcraft'); ?></span>
			</nav>
			<div id='addon_tabs' class='nav-content-slide'>
				<div class='active'>
					<?php
					if ( is_array($fc_addons) && count($fc_addons)>0 )
					{
						foreach ($fc_addons as $key => $addon) {
							$extra_class = isset($_GET['f3_activated']) && $_GET['f3_activated'] == $addon['plugin_id'] ? 'fc_highlight' : '';
							$extra_class = esc_html__($extra_class);
							?>
							<div class='addon addon-id-<?php echo esc_attr__($addon['plugin_id']).' '; echo $extra_class; ?>' <?php if ( !empty($addon['controller']) ) { ?> ng-controller='<?php echo esc_attr__($addon['controller']); ?>' <?php } ?> data-name='<?php echo esc_attr__($addon['title']); ?>'>
								<div class='addon-head ac-toggle' ng-click='Init()'>
									<div class='addon-logo-cover'>
										<img class='addon-logo' src='<?php echo esc_attr__($addon['logo']); ?>' alt='<?php echo esc_attr__($addon['title']); ?>'/>
									</div>
									<span class='addon-title'><?php echo esc_attr__($addon['title']); ?></span>
									<span class='toggle-angle'><i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i><i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i></span>
								</div>
								<div class='addon-content ac-inner'>
									<div>
										<?php
										$addon['content_fn']();
										?>
									</div>
								</div>
							</div>
							<?php
						}
					}
					else
					{
						?>
						<div class='no-addons'><?php esc_html_e('No Installed Addons','formcraft') ?></div>
						<?php
					}
					?>
				</div>
				<div class='new-addons'>
				</div>
			</div>
		</div>

		<div class='option-box state-{{Builder.Config.showOptions}}' id='form_options_box'>
			<nav class='nav-tabs-slide' data-content='#options_tabs'>
				<span class='active'><?php esc_html_e('General','formcraft'); ?></span>
				<span><?php esc_html_e('Email','formcraft'); ?></span>
				<span><?php esc_html_e('Embed','formcraft'); ?></span>
				<span><?php esc_html_e('Custom Text','formcraft'); ?></span>
				<span><?php esc_html_e('Advanced','formcraft'); ?></span>
			</nav>
			<div id='options_tabs' class='nav-content-slide'>
				<div class='active'>
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.form_disable'>
						<h3><?php esc_html_e('Disable form','formcraft'); ?></h3>
						<div class='option-description' ng-slide-toggle='Builder.Config.form_disable'><?php esc_html_e('Show this message when disabled: ','formcraft'); ?>
							<textarea ng-model='Builder.Config.form_disable_message'>
							</textarea>
						</div>
					</label>
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.disable_form_link'>
						<h3><?php esc_html_e('Disable form page','formcraft'); ?></h3>
						<div class='option-description'><?php esc_html_e('You can share this link directly to allow people to fill the form','formcraft'); ?>
							<textarea onclick='select()' rows='1' class='copy-code' readonly><?php echo get_site_url().'/form-view/'.$form_id; ?></textarea>
						</div>
					</label>
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.disable_multiple'>
						<h3><?php esc_html_e('Disable multiple submissions from same device','formcraft'); ?></h3>
						<div class='option-description' ng-slide-toggle='Builder.Config.disable_multiple'><?php esc_html_e('Show this message when disabled: ','formcraft'); ?>
							<textarea ng-model='Builder.Config.disable_multiple_message'>
							</textarea>
						</div>
					</label>
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.disable_after'>
						<h3><?php esc_html_e('Disable form when it reaches','formcraft'); ?><input type='text' ng-model='Builder.Config.disable_after_nos' class='width-60px'/><?php esc_html_e('submissions','formcraft'); ?></h3>
						<div class='option-description'><?php esc_html_e('Current submission counter', 'formcraft') ?>: <?php echo intval($counter); ?></div>
						<div class='option-description' ng-slide-toggle='Builder.Config.disable_after'><?php esc_html_e('Show this message when disabled: ','formcraft'); ?>
							<textarea ng-model='Builder.Config.form_disable_after_message'>
							</textarea>
						</div>
					</label>
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.disable_auto_scroll'>
						<h3><?php esc_html_e('Disable auto-scroll','formcraft'); ?></h3>
					</label>
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.save_progress'>
						<h3><?php esc_html_e('Enable auto-save form progress','formcraft'); ?></h3>
						<div class='option-description'><?php esc_html_e('Your users\' data is automatically saved as they type. If the form is not submitted, they can come back to the form later on, and will be able to continue from where they left. The data is stored for 60 days.','formcraft'); ?></div>
					</label>
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.Post_data'>
						<h3><?php esc_html_e('Send data to custom URL','formcraft'); ?>&nbsp;<a class='trigger-help' data-post-id='538'>(<?php esc_html_e('read more', 'formcraft'); ?>)</a></h3>
						<div class='option-description'>
							<?php esc_html_e('When the form has been successfully submitted, we will also send the form data to a URL of your choice:','formcraft'); ?>
							<input type='text' placeholder='http://example.com/my_app.php' class='width-100' ng-model='Builder.Config.webhook'>
							<label>
								<input type='radio' ng-model='Builder.Config.webhook_method' value='POST'>
								POST
							</label>&nbsp;
							<label>
								<input type='radio' ng-model='Builder.Config.webhook_method' value='POSTJSON'>
								POST (JSON)
							</label>&nbsp;
							<label>
								<input type='radio' ng-model='Builder.Config.webhook_method' value='GET'>
								GET
							</label>
						</div>
					</label>
				</div>
				<div class='email-setup-cover'>
					<div class='ac-toggle'>
						<?php esc_html_e('Email Setup','formcraft'); ?>
						<i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i>
						<i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i>
					</div>
					<div class='ac-inner'>
						<div class='width-100'>
							<div class='help-link'>
								<a class='trigger-help' data-post-id='33'><?php esc_html_e('what do I fill in here?', 'formcraft'); ?></a>
							</div>
							<div class='gmail-tip' ng-slide-toggle='Builder.Config.notifications.showTip'>
								If you are using Google Apps, you may need to enable the option ‘Enforce Less Secure Apps’. You can read about it <a target='_blank' href='https://support.google.com/a/answer/6260879?hl=en'>here</a>.
							</div>
							<div class='button-checkbox-group type-text email-method-select'>
								<label>
									<input update-label value='php' type='radio' ng-model='Builder.Config.notifications._method'/>
									<?php esc_html_e('WP Mail','formcraft'); ?>
								</label>
								<label>
									<input update-label value='smtp' type='radio' ng-model='Builder.Config.notifications._method'/>
									<?php esc_html_e('SMTP Method','formcraft'); ?>
								</label>
								<i data-placement='top' data-html='true' class='formcraft-icon tooltip-icon float-right' data-toggle='tooltip' title='<?php esc_attr_e('<strong>WP Mail:</strong> Uses default WordPress mail settings. If you have configured a third-party email plugin, check this option.<br/><strong>SMTP Method:</strong> Use custom SMTP settings to send emails. This method requires you to have SMTP config from your web host.', 'formcraft') ?>'>info_outline</i>
							</div>
							<div class='email-method-list'>
								<div ng-class='["email-php", "method-"+Builder.Config.notifications._method]'>
									<div class='input-group'>
										<label class='width-100'>
											<input type='text' placeholder='<?php esc_attr_e('Sender Name', 'formcraft'); ?>' ng-model='Builder.Config.notifications.general_sender_name'/>
										</label>
										<label class='width-100'>
											<input type='text' placeholder='<?php esc_attr_e('Sender Email', 'formcraft'); ?>' ng-model='Builder.Config.notifications.general_sender_email'/>
										</label>
									</div>
								</div>
								<div ng-class='["email-smtp", "method-"+Builder.Config.notifications._method]'>
									<div class='input-group'>
										<label class='width-100'>
											<input type='text' placeholder='<?php esc_attr_e('Sender Name', 'formcraft'); ?>' ng-model='Builder.Config.notifications.general_sender_name'/>
										</label>
										<label class='width-100'>
											<input type='text' placeholder='<?php esc_attr_e('Sender Email', 'formcraft'); ?>' ng-model='Builder.Config.notifications.general_sender_email'/>
										</label>
										<label class='width-100'>
											<input type='text' placeholder='<?php esc_attr_e('Username', 'formcraft'); ?>' ng-model='Builder.Config.notifications.smtp_sender_username'/>
										</label>
										<label class='width-100'>
											<input type='password' placeholder='<?php esc_attr_e('Password', 'formcraft'); ?>' ng-model='Builder.Config.notifications.smtp_sender_password'/>
										</label>
										<label class='width-100'>
											<input type='text' placeholder='<?php esc_attr_e('SMTP Host', 'formcraft'); ?>' ng-model='Builder.Config.notifications.smtp_sender_host'/>
										</label>
										<div>
											<label class='width-50'>
												<input type='text' placeholder='Port' ng-model='Builder.Config.notifications.smtp_sender_port'/>
											</label>
											<label class='width-50'>
												<select ng-model='Builder.Config.notifications.smtp_sender_security'>
													<option value=''><?php esc_html_e('Security Type', 'formcraft'); ?></option>
													<option value='none'><?php esc_html_e('None', 'formcraft'); ?></option>
													<option value='ssl'>SSL</option>
													<option value='tls'>TLS</option>
												</select>
											</label>
										</div>
									</div>
								</div>
								<br>
								<form ng-submit='testEmail()'>
									<span><?php esc_html_e('Send Test Email To:','formcraft'); ?></span>
									<i data-placement='top' data-html='true' class='formcraft-icon tooltip-icon float-right' data-toggle='tooltip' title='<?php esc_attr_e('You can send a test email to check if the email settings are working properly.<br>Add an email here, and press Enter','formcraft'); ?>'>info_outline</i>
									<input placeholder='dan@example.com' type='text' ng-model='Builder.TestEmails'/>
									<span class='float-right'><?php esc_html_e('press Enter to send', 'formcraft') ?></span>
									<div class='email-test-result' compile='TestEmailResult'></div>
									<span class='email-test-result-more' compile='TestEmailResultMore'></span>
								</form>
							</div>
						</div>
					</div>
					<div class='ac-toggle'><?php esc_html_e('Email Notifications','formcraft'); ?><i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i><i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i></div>
					<div class='ac-inner'>
						<div>
							<label>
								<?php esc_html_e('Send Email(s) To','formcraft'); ?>
								<i data-placement='top' data-html='true' class='formcraft-icon tooltip-icon float-right' data-toggle='tooltip' title='<?php esc_attr_e('When the form is submitted, an email will be sent to these addresses.<br>You can add multiple emails, separated by a comma.','formcraft'); ?>'>info_outline</i>								
								<input type="text" placeholder="dan@example.com, joe@example.com" ng-model='Builder.Config.notifications.recipients'>
							</label>
						</div>
						<br>
						<div>
							<label>
								<?php esc_html_e('Email Subject','formcraft'); ?>
								<i data-placement='top' data-html='true' class='formcraft-icon tooltip-icon float-right' data-toggle='tooltip' title='<?php esc_attr_e('You can add form values to subject, using the labels of fields, example: <strong>[Your Name]</strong>','formcraft'); ?>'>info_outline</i>
								<input type="text" placeholder="New Form Entry - [Form Name]" ng-model='Builder.Config.notifications.email_subject'>
							</label>
						</div>
						<br>
						<div>
							<div>
								<?php esc_html_e('Email Body','formcraft'); ?>
								<text-angular class='textangular' ng-model="Builder.Config.notifications.email_body"></text-angular>
							</div>
							<a class='trigger-help' data-post-id='186'>
								<?php esc_html_e('read more about customising email content', 'formcraft'); ?>
							</a>
							<label>
								<input type='checkbox' ng-model='Builder.Config.notifications.attach_images'>
								<?php esc_html_e('Attach file uploads to emails','formcraft'); ?>
							</label><br><br>
							<label>
								<input type='checkbox' ng-model='Builder.Config.notifications.form_layout'>
								<?php esc_html_e('Use form\'s multi-column layout in email body','formcraft'); ?>
							</label><br><br>
							<label>
								<input type='checkbox' ng-model='Builder.Config.notifications.removeEmptyTags'>
								<?php esc_html_e('Don\'t show tags in the email for empty-value or hidden fields','formcraft'); ?>
							</label><br><br>
						</div>
					</div>
					<div class='ac-toggle'><?php esc_html_e('Email Autoresponders','formcraft'); ?><i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i><i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i></div>
					<div class='ac-inner'>
						<div class='option-info'>
							<?php esc_html_e('Make sure to edit the email field in your form and check the option "Send Autoresponder" for fields where we should send autoresponder emails.', 'formcraft'); ?>
						</div>
						<br>
						<div>
							<label>
								<?php esc_html_e('Sender Name','formcraft'); ?>
								<input type='text' ng-model="Builder.Config.autoresponder.email_sender_name" placeholder='<?php esc_attr_e('John Smith','formcraft') ?>'/>
							</label>
						</div>
						<br>
						<div>
							<label>
								<?php esc_html_e('Sender Email','formcraft'); ?>
								<input type='text' ng-model="Builder.Config.autoresponder.email_sender_email" placeholder='<?php esc_attr_e('john@gmail.com','formcraft') ?>'/>
							</label>
						</div>
						<br>
						<div>
							<label>
								<?php esc_html_e('Email Subject','formcraft'); ?>
								<input type='text' ng-model="Builder.Config.autoresponder.email_subject" placeholder='<?php esc_attr_e('Thank you for your submission','formcraft') ?>'/>
							</label>
						</div>
						<br>
						<div>
							<label>
								<?php esc_html_e('Email Content','formcraft'); ?>
							</label>
						</div>
						<text-angular class='textangular' ng-model="Builder.Config.autoresponder.email_body"></text-angular>
						<a class='trigger-help' data-post-id='186'><?php esc_html_e('read more about customising email content', 'formcraft'); ?></a>
						<br>
						<div>
							<label>
								<?php esc_html_e('Attach Files','formcraft'); ?>
							</label>
							<div class='autoresponder-add-file' ng-repeat='file in Builder.Config.autoresponderFiles track by $index'>
								<input class='autoresponder-file' type='text' ng-model='file.name' placeholder='File Name'/>
								<input class='autoresponder-file' type='text' ng-model='file.url' placeholder='File URL'/>
								<i class='formcraft-icon' ng-click='removeAutoresponderFile($index)'>delete</i>
							</div>
							<button class='formcraft-button' ng-click='addAutoresponderFile()'><?php esc_html_e('Attach Files','formcraft'); ?></button>
						</div>						
					</div>
				</div>
				<div>
					<div class='ac-toggle'><?php esc_html_e('Dedicated Form Page','formcraft'); ?><i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i><i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i></div>
					<div class='ac-inner'>
						<span><?php esc_html_e('You can share this link directly to allow people to fill the form','formcraft'); ?>
							<textarea onclick='select()' rows='1' class='copy-code' readonly><?php echo get_site_url().'/form-view/'.$form_id; ?></textarea>
						</span>
						<br>
						<label>
							<input update-label type='checkbox' value='true' ng-model='Builder.Config.disable_form_link'>
							<?php esc_html_e('Disable This Page','formcraft'); ?>
						</label>
					</div>

					<div class='ac-toggle'><?php esc_html_e('Shortcode','formcraft'); ?><i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i><i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i></div>
					<div class='ac-inner'>
						<span><?php esc_html_e('Add this shortcode to your page / post','formcraft'); ?>.
							<textarea onclick='select()' rows='1' class='copy-code' readonly>[fc id='<?php echo $form_id; ?>'][/fc]</textarea>
							<a data-post-id='179' class='trigger-help'><?php esc_html_e('Read more','formcraft'); ?></a>							
						</span>
					</div>

					<div class='ac-toggle'><?php esc_html_e('Post / Page Editor','formcraft'); ?><i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i><i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i></div>
					<div class='ac-inner'>
						<img class='bg-image' src='<?php echo plugins_url( '../assets/images/add-form.png', __FILE__ ); ?>'/>
						<a data-post-id='66' class='trigger-help'><?php esc_html_e('Read more','formcraft'); ?></a>
					</div>

					<div class='ac-toggle'><?php esc_html_e('PHP Function','formcraft'); ?><i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i><i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i></div>
					<div class='ac-inner'>
						<textarea onclick='select()' rows='5' class='copy-code code-mode' readonly>
							&#x3C;?php
							  if (function_exists(&#x22;add_formcraft_form&#x22;)) {
							    add_formcraft_form(&#x22;[fc id=&#x27;<?php echo $form_id; ?>&#x27;][/fc]&#x22;);
							  }
							?&#x3E;
						</textarea>
						<a data-post-id='84' class='trigger-help'><?php esc_html_e('Read more','formcraft'); ?></a>
					</div>
				</div>
				<div class='custom-text-cover'>
					<textarea id='success-message' autosize ng-model='Builder.Config.Messages.success' placeholder='<?php esc_attr_e('Form success message (you can use HTML here)','formcraft'); ?>'></textarea>
					<input type='text' ng-model='Builder.Config.Messages.failed' placeholder='<?php esc_attr_e('Form failed message','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.is_required' placeholder='<?php esc_attr_e('Required field','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.is_invalid' placeholder='<?php esc_attr_e('Invalid value','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.min_char' placeholder='<?php esc_attr_e('Min [x] characters required','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.max_char' placeholder='<?php esc_attr_e('Max [x] characters allowed','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.min_files' placeholder='<?php esc_attr_e('Min [x] file(s) required','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.max_files' placeholder='<?php esc_attr_e('Max [x] file(s) allowed','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.max_file_size' placeholder='<?php esc_attr_e('Files bigger than [x] MB not allowed','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.allow_email' placeholder='<?php esc_attr_e('Invalid Email Address','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.allow_alphabets' placeholder='<?php esc_attr_e('Only alphabets allowed','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.allow_numbers' placeholder='<?php esc_attr_e('Only numbers allowed','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.allow_alphanumeric' placeholder='<?php esc_attr_e('Only alphabets and numbers allowed','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.allow_url' placeholder='<?php esc_attr_e('Invalid URL','formcraft'); ?>'/>
					<input type='text' ng-model='Builder.Config.Messages.allow_regexp' placeholder='<?php esc_attr_e('Invalid Expression','formcraft'); ?>'/>
				</div>
				<div class='advanced-settings-cover'>
					<label class='single-option'>
						<h3>
							<?php esc_html_e('Form Name','formcraft'); ?>: <input class='form-name-text' type='text' value='<?php echo esc_attr__($form_name); ?>' ng-model='Builder.Config.form_name'/>
						</h3>
					</label>
					<label class='single-option'>
						<h3>
							<?php esc_html_e('Redirect on Submit','formcraft'); ?>: <input class='redirect-text' placeholder='http://example.com/thank-you' type='text' value='<?php echo esc_attr__($form_name); ?>' ng-model='Builder.Config.redirect_main'/>
							<i data-placement='top' data-html='true' class='formcraft-icon' data-toggle='tooltip' title='<?php esc_attr_e('Your redirect URL can contain values from your form. If your form has a field with the label Your Name, your redirect URL can use: http://example.com/?name=[Your Name]','formcraft'); ?>'>info_outline</i>
						</h3>
					</label>
					<label class='single-option'>
						<h3><?php esc_html_e('Redirect','formcraft'); ?> <input type='text' ng-model='Builder.Config.Redirect_delay_seconds' class='redirect-delay-text'/> <?php esc_html_e('seconds after form submit','formcraft'); ?></h3>
					</label>
					<label class='single-option'>
						<h3><?php esc_html_e('Thousand separator: ','formcraft'); ?>
							<select ng-model='Builder.Config.thousand_separator' class='separator-text'>
								<option value=''><?php esc_html_e('None','formcraft'); ?></option>
								<option value=' '>space ( )</option>
								<option value=','>comma (,)</option>
								<option value='.'>period (.)</option>
							</select>
						</h3>
					</label>
					<label class='single-option'>
						<h3><?php esc_html_e('Decimal separator: ','formcraft'); ?>
							<select ng-model='Builder.Config.decimal_separator' class='separator-text'>
								<option value='.'>period (.)</option>
								<option value=','>comma (,)</option>
							</select>
						</h3>
					</label>
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.no_message_redirect'>
						<h3><?php esc_html_e('Don\'t show success message if redirect is enabled','formcraft'); ?></h3>
					</label>						
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.dont_submit_hidden'>
						<h3><?php esc_html_e('Don\'t submit values for hidden fields','formcraft'); ?></h3>
					</label>
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.dont_hide_empty'>
						<h3><?php esc_html_e('Don\'t hide empty fields in emails','formcraft'); ?></h3>
					</label>
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.disable_enter'>
						<h3><?php esc_html_e('Disable form submit on Enter','formcraft'); ?></h3>
					</label>
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.collect_ip'>
						<h3><?php esc_html_e('Collect User IP','formcraft'); ?></h3>
					</label>
					<label class='single-option has-checkbox'>
						<input update-label type='checkbox' value='true' ng-model='Builder.Config.disable_store'>
						<h3><?php esc_html_e('Delete entries','formcraft'); ?>
							<input type='text' ng-model='Builder.Config.disable_store_days' class='disable-store-days'/>
							<?php esc_html_e('days later','formcraft'); ?>
						</h3>
						<div class='option-description'>
							<?php esc_html_e('You can set this to 0 to disable storing form entries in the database altogether. You would be relying on emails then. Please note that this option applies to all entries, including those collected before this option was enabled, so this would delete past entries.','formcraft'); ?>
						</div>
					</label>
					<div class='single-option'>
						<h3>
							<?php esc_html_e('Custom JavaScript','formcraft'); ?>
						</h3>
						<div class='option-description'>
							<?php esc_html_e("Add any JavaScript code in here, and it will be executed on page load. You don't have to use &lt;script&gt; tags. Make sure this is valid JavaScript!", 'formcraft'); ?>
						</div>
						<div class='option-description'>
							<textarea  placeholder='console.log("It Works!");' autosize ng-model='Builder.Config.CustomJS' rows='7' class='custom-js'>
							</textarea>
						</div>
					</div>
					<div class='single-option'>
						<a target='_blank' href='<?php echo get_site_url(); ?>?formcraft3_export_form=<?php echo $form_id; ?>' class='formcraft-button'><?php esc_html_e('Export Form File','formcraft'); ?></a>
						<br/>
						<div class='option-description'>
							<?php esc_html_e('You can import this form template on any other WordPress site with the plugin installed.','formcraft'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id='form_styling_box' class='option-box  state-{{Builder.Config.showStyling}}'>
			<nav class='nav-tabs-slide' data-content='#styling_tabs'>
				<span class='active'><?php esc_html_e('General','formcraft'); ?></span>
				<span><?php esc_html_e('Color Scheme','formcraft'); ?></span>
				<span><?php esc_html_e('Background','formcraft'); ?></span>
			</nav>
			<div id='styling_tabs' class='nav-content-slide'>
				<div class='active'>
					<div class='ac-toggle'>
						<?php esc_html_e('General Styling','formcraft'); ?>
						<i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i>
						<i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i>						
					</div>
					<div class='ac-inner'>
						<label class='single-option has-checkbox'>
							<input update-label type='checkbox' ng-model='Builder.form_frame' ng-true-value='"hidden"' ng-false-value='"visible"'>
							<h3><?php esc_html_e('Remove Form Frame','formcraft'); ?></h3>
						</label>
						<label class='single-option has-checkbox'>
							<input update-label type='checkbox' ng-model='Builder.form_field_border' ng-true-value='"hidden"' ng-false-value='"visible"'>
							<h3><?php esc_html_e('Remove Field Borders','formcraft'); ?></h3>
						</label>						
						<label class='single-option has-checkbox'>
							<input update-label type='checkbox' ng-model='Builder.form_asterisk'>
							<h3>
								<?php esc_html_e('Hide','formcraft'); ?> <span>*</span>
							</h3>
						</label>
						<label class='single-option has-checkbox'>
							<input update-label type='checkbox' ng-model='Builder.hide_icons'>
							<h3><?php esc_html_e('Hide Field Icons','formcraft'); ?></h3>
						</label>
					</div>
					<div class='ac-toggle'>
						<?php esc_html_e('Font Styling','formcraft'); ?>
						<i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i>
						<i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i>						
					</div>
					<div class='ac-inner'>
						<div class='single-option'>
							<select class='standalone' ng-model='Builder.Config.font_family'>
								<option value="inherit"><?php esc_html_e('Default Font', 'formcraft'); ?></option>
								<optgroup label='<?php esc_html_e('General Fonts','formcraft'); ?>'>
									<option value="Helvetica, Arial, sans-serif">Helvetica / Arial</option>
									<option value="'Trebuchet MS', Helvetica, Arial, sans-serif">Trebuchet MS</option>
									<option value="'Courier New', Courier, monospace">Courier New</option>
									<option value="'Georgia', sans-serif">Georgia</option>
									<option value="'Times New Roman', sans-serif">Times New Roman</option>
								</optgroup>
								<optgroup label='<?php esc_html_e('Google Fonts','formcraft'); ?>'>
									<option value="Source Sans Pro">Source Sans Pro</option>
									<option value="Ubuntu">Ubuntu</option>
									<option value="Merriweather">Merriweather</option>
									<option value="Roboto">Roboto</option>
									<option value="Raleway">Raleway</option>
									<option value="Lato">Lato</option>
									<option value="Oswald">Oswald</option>
									<option value="Lora">Lora</option>
									<option value="Bitter">Bitter</option>
									<option value="Cabin">Cabin</option>
									<option value="Playfair Display">Playfair Display</option>
									<option value="Courgette">Courgette</option>
								</optgroup>
							</select>
							<div class='button-checkbox-group type-text'>
								<label ng-click='Builder.font_size = Builder.font_size + 5'><?php esc_html_e('Font Size', 'formcraft'); ?> +</label>
								<label ng-click='Builder.font_size = Builder.font_size - 5'><?php esc_html_e('Font Size', 'formcraft'); ?> -</label>
							</div>
						</div>
					</div>
					<div class='ac-toggle'>
						<?php esc_html_e('Field Styling','formcraft'); ?>
						<i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i>
						<i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i>						
					</div>
					<div class='ac-inner'>
						<div class='single-option'>
							<h3><?php esc_html_e('Field Layout', 'formcraft'); ?></h3>
							<div class='button-checkbox-group field-layout'>
								<label class='field-layout-one'>
									<span class='change-background'></span>
									<span class='change-background'></span>
									<span class='change-border'></span>
									<input type='radio' fc-placeholder-update name='fs_label' update-label value='inline' ng-model='Builder.label_style'>
								</label>
								<label class='field-layout-two'>
									<span class='change-background'></span>
									<span class='change-border'></span>
									<input type='radio' fc-placeholder-update name='fs_label' update-label value='placeholder' ng-model='Builder.label_style'>
								</label>
								<label class='field-layout-three'>
									<span class='change-background'></span>
									<span class='change-background'></span>
									<span class='change-border'></span>
									<input type='radio' fc-placeholder-update name='fs_label' update-label value='block' ng-model='Builder.label_style'>
								</label>
								<label class='field-layout-four'>
									<span class='change-background'></span>
									<span class='change-border'></span>
									<input type='radio' fc-placeholder-update name='fs_label' update-label value='floating' ng-model='Builder.label_style'>
								</label>
							</div>
						</div>
						<div class='single-option'>
							<h3><?php esc_html_e('Field Alignment', 'formcraft'); ?></h3>
							<div class='button-checkbox-group field-alignment'>
								<label class='field-alignment-one'>
									<span class='change-background'></span>
									<span class='change-background'></span>
									<span class='change-border'></span>
									<input type='radio' fc-placeholder-update name='fs_label' update-label value='left' ng-model='Builder.form_internal_alignment'>
								</label>
								<label class='field-alignment-two'>
									<span class='change-background'></span>
									<span class='change-background'></span>
									<span class='change-border'></span>
									<input type='radio' fc-placeholder-update name='fs_label' update-label value='center' ng-model='Builder.form_internal_alignment'>
								</label>
								<label class='field-alignment-three'>
									<span class='change-background'></span>
									<span class='change-background'></span>
									<span class='change-border'></span>
									<input type='radio' fc-placeholder-update name='fs_label' update-label value='right' ng-model='Builder.form_internal_alignment'>
								</label>
							</div>							
						</div>				
					</div>
					<div class='ac-toggle'>
						<?php esc_html_e('Your Logo','formcraft'); ?>
						<i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i>
						<i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i>						
					</div>
					<div class='ac-inner'>
						<div class='single-option'>
							<input type='text' placeholder='<?php esc_attr_e('Image URL','formcraft'); ?>' ng-model='Builder.Config.form_logo_url'/>
						</div>
					</div>
					<div class='ac-toggle'>
						<?php esc_html_e('Custom CSS','formcraft'); ?>
						<i class='formcraft-icon icon-type-down'>keyboard_arrow_down</i>
						<i class='formcraft-icon icon-type-up'>keyboard_arrow_up</i>						
					</div>
					<div class='ac-inner'>
						<div class='single-option'>
							<textarea autosize id='custom-css-textarea' ng-model='Builder.Config.Custom_CSS' rows='7'>
							</textarea>
							<a class='trigger-help' data-post-id='253'><?php esc_html_e('Guide to using Custom CSS', 'formcraft'); ?></a>
						</div>
					</div>
				</div>
				<div>
					<div class='option-head'>
						<?php esc_html_e('Choose A Color Scheme','formcraft'); ?>
					</div>
					<div class='single-option'>
						<div class='color-schemes colors'>
							<?php
							foreach ($color_scheme as $key => $value) {
								?>
							<label style='background: #<?php echo $value; ?>; border-color: <?php echo formcraft3_brightness('#'.$value, -30); ?>'>
								<input type='radio' update-label value='#<?php echo $value; ?>' ng-model='Color_scheme' name='radio_cs'>
							</label>								
								<?php
							}
							?>
						</div>
					</div>
					<div class='custom-color'>
						<div class='option-head'>
							<?php esc_html_e('Or Build A Custom One','formcraft'); ?>
						</div>
						<div class='single-option has-checkbox has-border-top'>
							<input type="text" value="#fff" angular-color class="color-picker" ng-model='Builder.Config.color_scheme_button'>							
							<h3>
								<?php esc_html_e('Base Color','formcraft'); ?>
							</h3>
						</div>
						<div class='single-option has-checkbox'>
							<input type="text" value="#fff" angular-color class="color-picker" ng-model='Builder.Config.color_scheme_font'>							
							<h3>
								<?php esc_html_e('Button Font Color','formcraft'); ?>
							</h3>
						</div>
						<div class='single-option has-checkbox'>
							<h3>
								<?php esc_html_e('Pagination Button Color','formcraft'); ?>
							</h3>
							<input type="text" value="#fff" angular-color class="color-picker" ng-model='Builder.Config.color_scheme_step'>
						</div>
						<div class='single-option has-checkbox'>
							<h3>
								<?php esc_html_e('Field Background Color','formcraft'); ?>
							</h3>
							<input type="text" value="#fff" angular-color class="color-picker" ng-model='Builder.Config.color_field_background'>
						</div>
						<div class='single-option has-checkbox'>
							<h3>
								<?php esc_html_e('General Font Color','formcraft'); ?>
							</h3>
							<input type="text" value="#fff" angular-color class="color-picker" ng-model='Builder.Config.font_color'>
						</div>
						<div class='single-option has-checkbox'>
							<h3>
								<?php esc_html_e('Field Font Color','formcraft'); ?>
							</h3>
							<input type="text" value="#777" angular-color class="color-picker" ng-model='Builder.Config.field_font_color'>
						</div>
					</div>
				</div>
				<div>
					<div class='option-head'>
						<?php esc_html_e('Choose A Form Background','formcraft'); ?>
					</div>
					<label class='single-option has-checkbox has-border-top'>
						<input type='radio' name='radio_bs_type' value='none' ng-model='Builder.Config.form_background_type'/>
						<h3><?php esc_html_e('Transparent / None', 'formcraft'); ?></h3>
					</label>
					<label class='single-option has-checkbox'>
						<input type='radio' name='radio_bs_type' value='white' ng-model='Builder.Config.form_background_type'/>
						<h3><?php esc_html_e('White', 'formcraft'); ?></h3>
					</label>
					<label class='single-option has-checkbox'>
						<input type='radio' name='radio_bs_type' value='image' ng-model='Builder.Config.form_background_type'/>
						<h3><?php esc_html_e('Preset Image', 'formcraft'); ?></h3>
						<div ng-slide-toggle='Builder.Config.form_background_type == "image"' class='color-schemes image-schemes hide-checkbox'>
							<?php
							foreach ($backgrounds as $key => $value) {
								?>
								<label ng-click='clearCustom()' title='<?php echo esc_attr__($value[0]); ?>' style='background: <?php echo esc_attr__($value[2]); ?>'>
									<input type='radio' name='radio_bs' update-label value='<?php echo esc_attr__($value[1]); ?>' ng-model='Builder.form_background'>
								</label>
								<?php
							}
							?>
						</div>
					</label>
					<label class='single-option has-checkbox form-background-cover'>
						<input type='radio' name='radio_bs_type' value='color' ng-model='Builder.Config.form_background_type'/>
						<h3><?php esc_html_e('Preset Color', 'formcraft'); ?></h3>
						<div ng-slide-toggle='Builder.Config.form_background_type == "color"' class='color-schemes image-schemes hide-checkbox'>
							<input type='text' value='{{Builder.form_background}}' ng-model='Builder.form_background_color' angular-color>
							<style>
							.form-background-cover .wp-color-result {
								background: {{Builder.form_background}} !important;
							}
							</style>
						</div>
					</label>
					<label class='single-option has-checkbox'>
						<input type='radio' name='radio_bs_type' value='custom' ng-model='Builder.Config.form_background_type'/>
						<h3><?php esc_html_e('Custom Image', 'formcraft'); ?></h3>
						<div ng-slide-toggle='Builder.Config.form_background_type == "custom"'>
							<input type='text' placeholder='Image URL' ng-model='Builder.form_background_custom_image'>
						</div>
					</label>
				</div>
			</div>
		</div>

		<div id='main-options-panel'>

			<a href='admin.php?page=formcraft-dashboard'>
				<i class='formcraft-icon'>keyboard_arrow_left</i><?php esc_html_e('Dashboard', 'formcraft'); ?>
			</a>

			<button id='form_options_button' ng-click='Builder.Config.showOptions = !Builder.Config.showOptions; Builder.Config.showStyling = false; Builder.Config.showAddons = false; Builder.Config.showLogic = false' class='active-{{Builder.Config.showOptions}}'>
				<i class='formcraft-icon'>settings</i><?php esc_html_e('Settings','formcraft') ?>
			</button>

			<button id='form_styling_button' ng-click='Builder.Config.showStyling = !Builder.Config.showStyling; Builder.Config.showAddons = false; Builder.Config.showOptions = false; Builder.Config.showLogic = false' class='active-{{Builder.Config.showStyling}}'>
				<i class='formcraft-icon'>format_size</i><?php esc_html_e('Styling','formcraft') ?>
			</button>

			<button id='form_addons_button' ng-click='Builder.Config.showAddons = !Builder.Config.showAddons; Builder.Config.showStyling = false; Builder.Config.showOptions = false; Builder.Config.showLogic = false' class='active-{{Builder.Config.showAddons}}'>
				<i class='formcraft-icon'>library_add</i><?php esc_html_e('Addons','formcraft') ?>
			</button>

			<button id='form_logic_button' ng-click='Builder.Config.showLogic = !Builder.Config.showLogic; Builder.Config.showStyling = false; Builder.Config.showOptions = false; Builder.Config.showAddons = false' class='active-{{Builder.Config.showLogic}}'>
				<i class='formcraft-icon'>shuffle</i><?php esc_html_e('Logic','formcraft') ?>
			</button>

			<button id='form_save_button' ng-click='saveForm()'>
				<div class='formcraft-loader'></div>
				<i class='formcraft-icon'>file_upload</i>
				<span class='save-text'><?php esc_html_e('Save','formcraft') ?></span>
				<span class='saving-text'><?php esc_html_e('Saving','formcraft') ?></span>
			</button>

			<button id='plugin-save' ng-click='saveForm("pluginInstalled")'></button>

			<button type='submit' ng-click='saveForm("preview")' id='form_preview_button'>
				<i class='formcraft-icon'>open_in_new</i><?php esc_html_e('Preview','formcraft') ?>
			</button>

			<button data-target='#help_modal' data-toggle='fc_modal' type='submit' id='help_button'>
				<i class='formcraft-icon'>help_outline</i><?php esc_html_e('Help','formcraft') ?>
			</button>		

		</div>

		<style>
		{{Builder.Config.Custom_CSS}}
	</style>
	<div class='form-cover-builder'>
		<span class='fc-spinner fc-spinner-form small'><div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div></span>
		<div id='form-cover-html' class='nos-{{Builder.FormElements.length}}{{Builder.FormElements[0].length}}' style='width: {{Builder.form_width}}'>
			<div class='fc-pagination-cover fc-pagination-{{Builder.FormElements.length}}'>
				<div class='fc-pagination width-100'>
					<div class='pagination-trigger' data-index='{{$index}}' ng-repeat='page in Builder.FormElements track by $index'>
						<span class='page-number'><span>{{$index+1}}</span></span>
						<span class='page-name'>{{Builder.Config.page_names[$index]}}</span>
						<!--RFH--><input type='text' ng-model='Builder.Config.page_names[$index]'><!--RTH-->
					</div>
				</div>
			</div>
			<!--RFH-->
			<div class='no-fields' ng-click='Builder.Config.show_fields = true'><?php esc_html_e('(No Fields)','formcraft'); ?></div>
			<div style='width: {{Builder.form_width}}' id='form-width-cover'><span><?php esc_html_e('Width','formcraft') ?></span><input ng-model='Builder.form_width' type='text'/>
			</div>
			<!--RTH-->
			<style scoped='scoped'>
			@media (max-width : 480px) {
				.fc_modal-dialog-<?php echo $form_id; ?> .fc-pagination-cover .fc-pagination
				{
					background-color: {{Builder.form_background}} !important;
				}
			}
			<?php
			do_action('formcraft_theme_custom_css', $form_id);
			?>
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .submit-cover .submit-button,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .fileupload-cover .button-file,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover .button,
			.formcraft-datepicker .ui-datepicker-header,
			.formcraft-datepicker .ui-datepicker-title
			{
				background: {{Builder.Config.color_scheme_button}};
				color: {{Builder.Config.color_scheme_font}};
			}
			.formcraft-datepicker td .ui-state-active,
			.formcraft-datepicker td .ui-state-hover,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .slider-cover .ui-slider-range
			{
				background: {{Builder.Config.color_scheme_button}};
			}
			#ui-datepicker-div.formcraft-datepicker .ui-datepicker-header,
			.formcraft-css .fc-form .field-cover>div.full hr
			{
				border-color: {{Builder.Config.color_scheme_button_dark}};
			}
			#ui-datepicker-div.formcraft-datepicker .ui-datepicker-prev:hover,
			#ui-datepicker-div.formcraft-datepicker .ui-datepicker-next:hover,
			#ui-datepicker-div.formcraft-datepicker select.ui-datepicker-month:hover,
			#ui-datepicker-div.formcraft-datepicker select.ui-datepicker-year:hover
			{
				background-color: {{Builder.Config.color_scheme_button_dark}};
			}
			.formcraft-css .fc-pagination>div.active .page-number,
			.formcraft-css .form-cover-builder .fc-pagination>div:first-child .page-number
			{
				background-color: {{Builder.Config.color_scheme_step}};
				color: {{Builder.Config.color_scheme_font}};
			}
			#ui-datepicker-div.formcraft-datepicker table.ui-datepicker-calendar th,
			#ui-datepicker-div.formcraft-datepicker table.ui-datepicker-calendar td.ui-datepicker-today a,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .star-cover label,
			html .formcraft-css .fc-form.label-floating .form-element .field-cover.has-focus>span,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .customText-cover a,
			.formcraft-css .prev-next>div span:hover
			{
				color: {{Builder.Config.color_scheme_button}};
			}
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .customText-cover a:hover
			{
				color: {{Builder.Config.color_scheme_button_dark}};
			}
			html .formcraft-css .fc-form.fc-form-<?php echo $form_id; ?>.label-floating .form-element .field-cover > span
			{
				color: {{Builder.Config.font_color}};
			}
			html .formcraft-css .fc-form .final-success .final-success-check {
				border: 2px solid {{Builder.Config.font_color}};
			}
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover input[type="text"],
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover input[type="email"],
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover input[type="password"],
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover input[type="tel"],
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover textarea,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover select,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover .time-fields-cover,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover .awesomplete ul
			{
				color: {{Builder.Config.field_font_color}};
			}
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover input[type="text"],
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover input[type="password"],
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover input[type="email"],
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover input[type="radio"],
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover input[type="checkbox"],
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover input[type="tel"],
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover select,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover textarea
			{
				background-color: {{Builder.Config.color_field_background}};
			}
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover input[type="radio"]:checked,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .field-cover input[type="checkbox"]:checked {
				border-color: {{Builder.Config.color_scheme_button_dark}};
				background: {{Builder.Config.color_scheme_button}};				
			}
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .star-cover label .star
			{
				text-shadow: 0px 1px 0px {{Builder.Config.color_scheme_button_dark}};
			}
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .slider-cover .ui-slider-range
			{
				box-shadow: 0px 1px 1px {{Builder.Config.color_scheme_button_dark}} inset;
			}
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .fileupload-cover .button-file
			{
				border-color: {{Builder.Config.color_scheme_button_dark}};
			}
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .form-element-html input[type="password"]:focus,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .form-element-html input[type="email"]:focus,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .form-element-html input[type="tel"]:focus,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .form-element-html input[type="text"]:focus,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .form-element-html textarea:focus,
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .form-element-html select:focus
			{
				border-color: {{Builder.Config.color_scheme_button}};
			}
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?> .form-element .form-element-html .field-cover .is-read-only:focus {
				border-color: #ccc;
			}
			.formcraft-css .fc-form.fc-form-<?php echo $form_id; ?>
			{
				font-family: {{Builder.Config.font_family}};
			}
			@media (max-width : 480px) {
				html .dedicated-page,
				html .dedicated-page .formcraft-css .fc-pagination > div.active
				{
					background: {{Builder.form_background}};
				}
			}
		</style>
		<div class='form-cover'>
			<form ng-init='builderInit()' data-auto-scroll='{{Builder.Config.disable_auto_scroll}}' data-no-message-redirect='{{Builder.Config.no_message_redirect}}' data-thousand='{{Builder.Config.thousand_separator}}' data-decimal='{{Builder.Config.decimal_separator}}' data-delay='{{Builder.Config.Redirect_delay_seconds}}' data-id='<?php echo $form_id; ?>' class='fc-form fc-form-<?php echo $form_id; ?> label-{{Builder.label_style}} align-{{Builder.form_align}} fc-temp-class field-border-{{Builder.form_field_border}} frame-{{Builder.form_frame}} save-form-{{Builder.Config.save_progress}} dont-submit-hidden-{{Builder.Config.dont_submit_hidden}} remove-asterisk-{{Builder.form_asterisk}} icons-hide-{{Builder.hide_icons}} field-alignment-{{Builder.form_internal_alignment}} disable-enter-{{Builder.Config.disable_enter}}' style='width: {{Builder.form_width}}; color: {{Builder.Config.font_color}}; font-size: {{Builder.font_size}}%; background: {{Builder.form_background}}'>
				<div class='form-page form-page-{{$index}}' ng-repeat='page in Builder.FormElements track by $index' data-index='{{$index}}'>
					<!--RFH-->
					<div class='delete-page' ng-click='removeFormPage($index)' title='<?php esc_attr_e('Delete Page','formcraft'); ?>'>
						<i class='formcraft-icon'>delete</i>
					</div>
					<!--RTH-->
					<div ui-sortable="sortableOptions[$index]" ng-model='page' class='form-page-content'>
						<div ng-class-odd="'odd'" data-identifier='{{element.identifier}}' ng-class='["form-element", "form-element-"+element.identifier, "options-"+element.showOptions, "form-element-"+$index, "default-"+element.elementDefaults.hidden_default, "form-element-type-"+element.type, "is-required-"+element.elementDefaults.required]' ng-class-even="'even'" ng-repeat='element in page track by element.identifier' data-index='{{$index}}' style='width: {{element.elementDefaults.field_width}}'>
							<div ng-click='toggleOptions($event, $parent.$index, $index)' watch-show-options='{{element.showOptions}}' class='form-element-html' compile='element.element'>
							</div>
							<!--RFH-->
							<div class='form-options animate-{{element.showOptionsAnimate}} state-{{element.showOptions}}'>
								<div class='options-head'>
									<div title='<?php esc_attr_e('Field ID','formcraft'); ?>' class='field-id'>{{element.identifier}}</div>
									<i title='<?php esc_attr_e('Delete Field','formcraft'); ?>' ng-click='removeFormElement($parent.$index, $index)' class='delete formcraft-icon'>add_circle</i>
									<i title='<?php esc_attr_e('Duplicate Field','formcraft'); ?>' ng-click='duplicateFormElement($parent.$index, $index)' class='duplicate formcraft-icon'>add_circle</i>
									<i title='<?php esc_attr_e('Minimize Options','formcraft'); ?>' ng-click='toggleOptions($event, $parent.$index, $index)' class='minimize formcraft-icon'>remove_circle</i>									
								</div>
								<div class='options-main' compile='element.elementOptions'></div>
							</div>
							<!--RTH-->
						</div>
					</div>
				</div>
			</form>
			<div class='prev-next prev-next-{{Builder.FormElements.length}}' style='width: {{Builder.form_width}}; color: {{Builder.Config.font_color}}; font-size: {{Builder.font_size}}%; background: {{Builder.form_background}}'>
				<div>
					<!--RFH-->
					<label><input type='text' ng-model='Builder.prevText'/></label>
					<!--RTH-->
					<span class='inactive page-prev'><i class='formcraft-icon'>keyboard_arrow_left</i>{{Builder.prevText}}</span></div>
				<div>
					<!--RFH-->
					<label><input type='text' ng-model='Builder.nextText'/></label>
					<!--RTH-->
					<span class='page-next'>{{Builder.nextText}}<i class='formcraft-icon'>keyboard_arrow_right</i></span></div>
			</div>
		</div>
	</div>
</div>
<div class="fc_modal fc_fade" id="general_modal">
	<div class="fc_modal-dialog">
		<div class="fc_modal-content">
			<div class="fc_modal-header">
				<button class='fc_close' type="button" class="close" data-dismiss="fc_modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="fc_modal-title"><?php esc_html_e('Form Options','formcraft'); ?></h4>
			</div>
		</div>
	</div>
</div>
<div class="fc_modal fc_fade" id="icons_modal">
	<div class="fc_modal-dialog">
		<div class="fc_modal-content">
			<div class="fc_modal-header">
				<button class='fc_close' type="button" class="close" data-dismiss="fc_modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="fc_modal-title"><?php esc_html_e('Select Icon','formcraft'); ?></h4>
			</div>
			<div class='fc_modal-body'>
				<label class='icon-parent-label formcraft-icon-type-{{icon.Value}}' ng-repeat='icon in listIcons track by $index'>
					<input ng-click='selectIcon(icon.Value)' type='radio' name='{{element.identifier}}_icon' update-label ng-model='element.elementDefaults.selectedIcon' value='{{::icon.Value}}'/><i class='formcraft-icon'>{{::icon.Value}}</i>
				</label>				
			</div>
		</div>
	</div>
</div>
<div class="fc_modal fc_fade" id="help_modal">
	<div class="fc_modal-dialog">
		<div class="fc_modal-content">
			<div class="fc_modal-body">
				<div id='help-menu'>
					<form id='help-search'>
						<input type='text' placeholder='<?php esc_attr_e('Search','formcraft'); ?>'>
					</form>
					<ul>
					</ul>
				</div>
				<div id='help-content'>
					<div class='formcraft-loader'></div>
					<div id='help-content-content'></div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

</div>
