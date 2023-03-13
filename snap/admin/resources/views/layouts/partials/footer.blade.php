		<snap-disabler id="snap-disabler"></snap-disabler>

		<snap-modal id="snap-modal" ref="modal"></snap-modal>
	</div>

    <script type="text/x-template" id="snap-modal-template">
		<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="snap-modal" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="snap-loader" v-if="loading"></div>
				<div class="modal-content" id="snap-modal-content" v-html="content">
					<slot name="content"></slot>
				</div>
			</div>
		</div>
    </script>
</body>
</html>