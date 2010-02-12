{* SVN $Id$ *}

<!-- [catalog] {{{ -->
<widget target="category" template="category_description.tpl" visible="{category.description}">
<!-- [/catalog] }}} -->

<!-- [main] {{{ -->
<widget target="main" mode="accessDenied" template="access_denied.tpl">
<widget module="GreetVisitor" target="main" mode="" template="modules/GreetVisitor/greet_visitor.tpl" visible="{greetVisitor&!page}">
<widget target="main" mode="" template="welcome.tpl" name="welcomeWidget" visible="{!page}">
<widget target="main" template="pages.tpl">
<!-- [/main] }}} -->

<!-- [help] {{{ -->
<widget target="help" mode="terms_conditions" template="common/dialog.tpl" body="terms_conditions.tpl" head="Terms & Conditions">
<widget target="help" mode="privacy_statement" template="common/dialog.tpl" body="privacy_statement.tpl" head="Privacy statement">
<widget target="recover_password" mode="" template="common/dialog.tpl" head="Forgot password?" body="recover_password.tpl">
<widget target="recover_password" mode="recoverMessage" template="common/dialog.tpl" head="Recover password" body="recover_message.tpl">
<widget target="help" mode="contactus" template="common/dialog.tpl" body="contactus.tpl" head="Contact us">
<widget target="help" mode="contactusMessage" template="common/dialog.tpl" body="contactus_message.tpl" head="Message is sent">
<!-- [/help] }}} -->

<!-- [shopping_cart] {{{ -->
<widget target="cart" template="common/dialog.tpl" body="shopping_cart/body.tpl" head="Shopping cart">
<!-- [/shopping_cart] }}} -->

<!-- [profile] {{{ -->
<widget target="profile" mode="login" template="common/dialog.tpl" head="Authentication" body="authentication.tpl">
<widget target="profile" mode="account" template="common/dialog.tpl" head="Your account" body="account.tpl">
<widget target="login" template="common/dialog.tpl" body="authentication.tpl" head="Authentication">
<widget target="profile" mode="register" class="XLite_View_RegisterForm" template="common/dialog.tpl" head="New customer" body="register_form.tpl" name="registerForm" IF="!showAV"/>
<widget target="profile" mode="success" template="common/dialog.tpl" head="Registration complete" body="register_success.tpl">
<widget target="profile" mode="modify" class="XLite_View_RegisterForm" template="common/dialog.tpl" head="Modify profile" body="profile.tpl" name="profileForm" IF="!showAV"/>
<widget target="profile" mode="delete" template="common/dialog.tpl" head="Delete profile - Confirmation" body="delete_profile.tpl">
<!-- [/profile] }}} -->

<!-- [checkout] {{{ -->
<widget target="checkout" mode="register,paymentMethod,details" template="common/dialog.tpl" body="checkout/checkout.tpl" head="Shopping cart" IF="!showAV"/>
<!-- [/checkout] }}} -->

<!-- [order] {{{ -->
<widget target="order_list" template="order/search.tpl">
<widget target="order" template="common/dialog.tpl" body="order/order.tpl" head="Order # {order.order_id}">
<!-- [/order] }}} -->

<!-- [modules] {{{ -->
<widget module="GiftCertificates" target="add_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/add_gift_certificate.tpl" head="Add gift certificate">
<widget module="GiftCertificates" target="gift_certificate_ecards" template="common/dialog.tpl" body="modules/GiftCertificates/select_ecard.tpl" head="Select e-Card">
<widget module="GiftCertificates" target="check_gift_certificate" template="common/dialog.tpl" body="modules/GiftCertificates/check_gift_certificate.tpl" head="Verify gift certificate">
<widget module="GiftCertificates" target="gift_certificate_info" template="common/dialog.tpl" body="modules/GiftCertificates/gift_certificate_info.tpl" head="Gift certificate">
<widget module="Newsletters" template="modules/Newsletters/newsletters.tpl">
<widget class="XLite_Module_AdvancedSearch_View_AdvancedSearch" module="AdvancedSearch" mode="" target="advanced_search" template="common/dialog.tpl" />
<widget module="WishList" target="wishlist,product" mode="MessageSent" template="common/dialog.tpl" body="modules/WishList/message.tpl" head="Message has been sent">
<widget module="WishList" target="wishlist" head="Wish List" template="common/dialog.tpl" body="modules/WishList/wishlist.tpl">
<!-- [/modules] }}} -->

