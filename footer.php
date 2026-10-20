</main><!-- #content -->

<?php trijaya_footer_render(); ?>

<?php
// Widget AI Chat (Groq) — dirender bila aktif & terkonfigurasi.
if ( get_theme_mod( 'trijaya_chat_enable', true ) && trijaya_chat_configured() ) {
	get_template_part( 'template-parts/chat', 'widget' );
}
?>

<?php wp_footer(); ?>
</body>
</html>
