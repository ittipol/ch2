<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

<div class="form-row">
  <?php echo Form::label('', 'ระบุตำแหน่บนแผนที่'); ?>
  <input id="pac-input" class="controls" type="text" placeholder="Search Box">
  <div id="map"></div>
</div>

<script type="text/javascript">
  const map = new Map();
  map.setInputName('latitude','longitude');
  map.initialize();
  @if(!empty($_geographic))
  map.setLocation({!!$_geographic!!});
  @endif
</script>