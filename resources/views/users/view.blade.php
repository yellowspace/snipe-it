@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('admin/users/general.view_user', ['name' => html_entity_decode($user->present()->fullName(), ENT_QUOTES | ENT_XML1, 'UTF-8')]) }}
@parent
@stop

{{-- Page content --}}
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs hidden-print">

        <li class="active">
          <a href="#details" data-toggle="tab">
            <span class="hidden-lg hidden-md">
            <i class="fa fa-info-circle"></i>
            </span>
            <span class="hidden-xs hidden-sm">{{ trans('admin/users/general.info') }}</span>
          </a>
        </li>

        <li>
          <a href="#asset_tab" data-toggle="tab">
            <span class="hidden-lg hidden-md">
            <i class="fa fa-barcode"></i>
            </span>
            <span class="hidden-xs hidden-sm">{{ trans('general.assets') }}</span>
          </a>
        </li>

        <li>
          <a href="#licenses_tab" data-toggle="tab">
            <span class="hidden-lg hidden-md">
            <i class="fa fa-floppy-o"></i>
            </span>
            <span class="hidden-xs hidden-sm">{{ trans('general.licenses') }}</span>
          </a>
        </li>

        <li>
          <a href="#accessories_tab" data-toggle="tab">
            <span class="hidden-lg hidden-md">
            <i class="fa fa-keyboard-o"></i>
            </span> <span class="hidden-xs hidden-sm">{{ trans('general.accessories') }}</span>
          </a>
        </li>

        <li>
          <a href="#consumables_tab" data-toggle="tab">
            <span class="hidden-lg hidden-md">
            <i class="fa fa-tint"></i></span>
            <span class="hidden-xs hidden-sm">{{ trans('general.consumables') }}</span>
          </a>
        </li>

        <li>
          <a href="#files_tab" data-toggle="tab">
            <span class="hidden-lg hidden-md">
            <i class="fa fa-paperclip"></i></span>
            <span class="hidden-xs hidden-sm">{{ trans('general.file_uploads') }}</span>
          </a>
        </li>

        <li>
          <a href="#history_tab" data-toggle="tab">
            <span class="hidden-lg hidden-md">
            <i class="fa fa-clock-o"></i></span>
            <span class="hidden-xs hidden-sm">{{ trans('general.history') }}</span>
          </a>
        </li>

        @if ($user->managedLocations()->count() >= 0 )
        <li>
          <a href="#managed_tab" data-toggle="tab">
            <span class="hidden-lg hidden-md">
            <i class="fa fa-clock-o"></i></span>
            <span class="hidden-xs hidden-sm">{{ trans('admin/users/table.managed_locations') }}</span>
          </a>
        </li>
        @endif

        @can('update', $user)
          <li class="dropdown pull-right">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="fa fa-gear"></i> {{ trans('button.actions') }}
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="{{ route('users.edit', $user->id) }}">{{ trans('admin/users/general.edit') }}</a></li>
              <li><a href="{{ route('clone/user', $user->id) }}">{{ trans('admin/users/general.clone') }}</a></li>
              @if ((Auth::user()->id !== $user->id) && (!config('app.lock_passwords')) && ($user->deleted_at==''))
                <li><a href="{{ route('users.destroy', $user->id) }}">{{ trans('button.delete') }}</a></li>
              @endif
            </ul>
          </li>
        @endcan
      </ul>

      <div class="tab-content">
        <div class="tab-pane active" id="details">
          <div class="row">
            @if ($user->deleted_at!='')
              <div class="col-md-12">
                <div class="callout callout-warning">
                  <i class="icon fa fa-warning"></i>
                  {{ trans('admin/users/message.user_deleted_warning') }}
                  @can('update', $user)
                      <a href="{{ route('restore/user', $user->id) }}">{{ trans('admin/users/general.restore_user') }}</a>
                  @endcan
                </div>
              </div>
            @endif
            <div class="col-md-2 text-center">
                <img src="{{ $user->present()->gravatar }}" class="avatar img-thumbnail hidden-print">
            </div>

            <div class="col-md-8">
              <div class="table table-responsive">
                <table class="table table-striped">
                  @if (!is_null($user->company))
                    <tr>
                        <td class="text-nowrap">{{ trans('general.company') }}</td>
                        <td>{{ $user->company->name }}</td>
                    </tr>
                  @endif

                  <tr>
                    <td class="text-nowrap">{{ trans('admin/users/table.name') }}</td>
                    <td>{{ html_entity_decode($user->present()->fullName(), ENT_QUOTES | ENT_XML1, 'UTF-8') }}</td>
                  </tr>
                  <tr>
                    <td class="text-nowrap">{{ trans('admin/users/table.username') }}</td>
                    <td>{{ $user->username }}</td>
                  </tr>

                    <tr>
                      <td class="text-nowrap">{{ trans('general.groups') }}</td>
                      <td>
                        @if ($user->groups->count() > 0)
                            @foreach ($user->groups as $group)

                              @can('superadmin')
                                  <a href="{{ route('groups.show', $group->id) }}" class="label label-default">{{ $group->name }}</a>
                            @else
                              {{ $group->name }}
                            @endcan

                            @endforeach
                        @else
                          --
                        @endif

                      </td>
                    </tr>


                  @if ($user->jobtitle)
                  <tr>
                    <td class="text-nowrap">{{ trans('admin/users/table.job') }}</td>
                    <td>{{ $user->jobtitle }}</td>
                  </tr>
                  @endif

                  @if ($user->employee_num)
                  <tr>
                    <td class="text-nowrap">{{ trans('admin/users/table.employee_num') }}</td>
                    <td>{{ $user->employee_num }}</td>
                  </tr>
                  @endif

                  @if ($user->manager)
                  <tr>
                    <td class="text-nowrap">{{ trans('admin/users/table.manager') }}</td>
                    <td>
                      <a href="{{ route('users.show', $user->manager->id) }}">{{ $user->manager->getFullNameAttribute() }}</a>

                      </td>
                  </tr>
                  @endif

                  @if ($user->email)
                  <tr>
                    <td class="text-nowrap">{{ trans('admin/users/table.email') }}</td>
                    <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                  </tr>
                  @endif

                  @if ($user->website)
                    <tr>
                      <td class="text-nowrap">{{ trans('general.website') }}</td>
                      <td><a href="{{ $user->website }}" target="_blank">{{ $user->website }}</a></td>
                    </tr>
                  @endif

                  @if ($user->phone)
                  <tr>
                    <td class="text-nowrap">{{ trans('admin/users/table.phone') }}</td>
                    <td><a href="tel:{{ $user->phone }}">{{ $user->phone }}</a></td>
                  </tr>
                  @endif

                  @if ($user->userloc)
                  <tr>
                    <td class="text-nowrap">{{ trans('admin/users/table.location') }}</td>
                    <td>{{ link_to_route('locations.show', $user->userloc->name, [$user->userloc->id]) }}</td>


                  </tr>
                  @endif
                    @if ($user->last_login)
                      <tr>
                        <td class="text-nowrap">{{ trans('general.last_login') }}</td>
                        <td>{{ \App\Helpers\Helper::getFormattedDateObject($user->last_login, 'datetime', false) }}</td>
                      </tr>
                    @endif

                    @if (!is_null($user->department))
                      <tr>
                        <td class="text-nowrap">{{ trans('general.department') }}</td>
                        <td><a href="{{ route('departments.show', $user->department) }}">{{ $user->department->name }}</a></td>
                      </tr>
                    @endif
                  @if ($user->created_at)
                  <tr>
                    <td>{{ trans('general.created_at') }}</td>
                    <td>{{ $user->created_at->format('F j, Y h:iA') }}</td>
                  </tr>
                  @endif
                    <tr>
                      <td class="text-nowrap">{{ trans('general.login_enabled') }}</td>
                      <td>{{ ($user->activated=='1') ? trans('general.yes') : trans('general.no') }}</td>
                    </tr>

                    @if ($user->activated=='1')
                      <tr>
                        <td class="text-nowrap">{{ trans('admin/users/general.two_factor_active') }}</td>
                        <td>{{ ($user->two_factor_active()) ? trans('general.yes') : trans('general.no') }}</td>
                      </tr>
                      <tr>
                        <td class="text-nowrap">{{ trans('admin/users/general.two_factor_enrolled') }}</td>
                        <td class="two_factor_resetrow">
                          <div class="row">
                          <div class="col-md-1" id="two_factor_reset_toggle">
                            {{ ($user->two_factor_active_and_enrolled()) ? trans('general.yes') : trans('general.no') }}
                          </div>

                          @if ((Auth::user()->isSuperUser()) && ($snipeSettings->two_factor_enabled!='0'))
                            <div class="col-md-11">
                            <a class="btn btn-default btn-sm pull-left" id="two_factor_reset" style="margin-right: 10px;"> {{ trans('admin/settings/general.two_factor_reset') }}</a>
                            <span id="two_factor_reseticon">
                            </span>
                            <span id="two_factor_resetresult">
                            </span>
                            <span id="two_factor_resetstatus">
                            </span>

                                <br><br><p class="help-block">{{ trans('admin/settings/general.two_factor_reset_help') }}</p>
                            </div>
                          </div>
                       @endif

                        </td>
                      </tr>

                     @endif


                </table>
              </div>
            </div> <!--/col-md-8-->

            <!-- Start button column -->
            <div class="col-md-2">
              @can('update', $user)
                <div class="col-md-12">
                  <a href="{{ route('users.edit', $user->id) }}" style="width: 100%;" class="btn btn-sm btn-default hidden-print">{{ trans('admin/users/general.edit') }}</a>
                </div>
              @endcan

              @can('create', $user)
                <div class="col-md-12" style="padding-top: 5px;">
                  <a href="{{ route('clone/user', $user->id) }}" style="width: 100%;" class="btn btn-sm btn-default hidden-print">{{ trans('admin/users/general.clone') }}</a>
                </div>
               @endcan

                @can('view', $user)
                <div class="col-md-12" style="padding-top: 5px;">
                  <a href="{{ route('users.print', $user->id) }}" style="width: 100%;" class="btn btn-sm btn-default hidden-print">{{ trans('admin/users/general.print_assigned') }}</a>
                </div>
                @endcan
                @can('delete', $user)
                  @if ($user->deleted_at=='')
                    <div class="col-md-12" style="padding-top: 5px;">
                      <form action="{{route('users.destroy',$user->id)}}" method="POST">
                        {{csrf_field()}}
                        {{ method_field("DELETE")}}
                        <button style="width: 100%;" class="btn btn-sm btn-warning hidden-print">{{ trans('button.delete')}}</button>
                      </form>
                    </div>
                    <div class="col-md-12" style="padding-top: 5px;">
                      <form action="{{ route('users/bulkedit') }}" method="POST">
                        <!-- CSRF Token -->
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="ids[{{ $user->id }}]" value="{{ $user->id }}" />
                        <button style="width: 100%;" class="btn btn-sm btn-danger hidden-print">{{ trans('button.checkin_and_delete') }}</button>
                      </form>
                    </div>
                  @else
                    <div class="col-md-12" style="padding-top: 5px;">
                      <a href="{{ route('restore/user', $user->id) }}" style="width: 100%;" class="btn btn-sm btn-warning hidden-print">{{ trans('button.restore') }}</a>
                    </div>
                  @endif
                @endcan
            </div>
            <!-- End button column -->
          </div> <!--/.row-->
        </div><!-- /.tab-pane -->

        <div class="tab-pane" id="asset_tab">
          <!-- checked out assets table -->
          <div class="table-responsive">
            <table class="display table table-striped">
              <thead>
                <tr>
                  <th class="col-md-3">{{ trans('admin/hardware/table.asset_model') }}</th>
                  <th class="col-md-2">{{ trans('admin/hardware/table.asset_tag') }}</th>
                  <th class="col-md-2">{{ trans('general.name') }}</th>
                  <th class="col-md-1 hidden-print">{{ trans('general.action') }}</th>
                </tr>
              </thead>
              <tbody>
              @if ($user->assets)
                @foreach ($user->assets as $asset)
                <tr>
                  <td>
                    @if (($asset->model) && ($asset->physical=='1'))
                      <a href="{{ route('models.show', $asset->model->id) }}">{{ $asset->model->name }}</a>
                    @endif
                  </td>
                  <td>
                    @can('view', $asset)
                      <a href="{{ route('hardware.show', $asset->id) }}">{{ $asset->asset_tag }}</a>
                    @endcan
                  </td>
                  <td>{!! $asset->present()->nameUrl() !!}</td>
                  <td class="hidden-print">
                    @can('checkin', $asset)
                      <a href="{{ route('checkin/hardware', array('assetId'=> $asset->id, 'backto'=>'user')) }}" class="btn btn-primary btn-sm hidden-print">{{ trans('general.checkin') }}</a>
                    @endcan
                  </td>
                </tr>
                @if($settings->show_assigned_assets)
                  @foreach ($asset->assignedAssets as $asset)
                      <tr>
                        <td>
                          @if ($asset->physical=='1')
                            <a href="{{ route('models.show', $asset->model->id) }}">{{ ' – '.$asset->model->name }}</a>
                          @endif
                        </td>
                        <td>
                          @can('view', $asset)
                            <a href="{{ route('hardware.show', $asset->id) }}">{{ $asset->asset_tag }}</a>
                          @endcan
                        </td>
                        <td>{!! $asset->present()->nameUrl() !!}</td>
                        <td class="hidden-print">
                          @can('checkin', $asset)
                            <a href="{{ route('checkin/hardware', array('assetId'=> $asset->id, 'backto'=>'user')) }}" class="btn btn-primary btn-sm hidden-print">{{ trans('general.checkin') }}</a>
                          @endcan
                        </td>
                      </tr>
                  @endforeach
                @endif
                @endforeach
                @endif
              </tbody>
            </table>
          </div>
        </div><!-- /asset_tab -->

        <div class="tab-pane" id="licenses_tab">
          <div class="table-responsive">
            <table class="display table table-hover">
              <thead>
                <tr>
                  <th class="col-md-5">{{ trans('general.name') }}</th>
                  <th class="col-md-6">{{ trans('admin/hardware/form.serial') }}</th>
                  <th class="col-md-1 hidden-print">{{ trans('general.action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($user->licenses as $license)
                <tr>
                  <td>
                    {!! $license->present()->nameUrl() !!}
                  </td>
                  <td>
                    @can('viewKeys', $license)
                    {!! $license->present()->serialUrl() !!}
                    @else
                      ------------
                    @endcan
                  </td>
                  <td class="hidden-print">
                    @can('update', $license)
                      <a href="{{ route('licenses.checkin', array('licenseseat_id'=> $license->pivot->id, 'backto'=>'user')) }}" class="btn btn-primary btn-sm hidden-print">{{ trans('general.checkin') }}</a>
                     @endcan
                  </td>
                </tr>
                @endforeach
              </tbody>
          </table>
          </div>
        </div><!-- /licenses-tab -->

        <div class="tab-pane" id="accessories_tab">
          <div class="table-responsive">
            <table class="display table table-hover">
              <thead>
                <tr>
                  <th class="col-md-5">{{ trans('general.name') }}</th>
                  <th class="col-md-1 hidden-print">{{ trans('general.action') }}</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($user->accessories as $accessory)
                  <tr>
                    <td>{!!$accessory->present()->nameUrl()!!}</td>
                    <td class="hidden-print">
                      @can('checkin', $accessory)
                        <a href="{{ route('checkin/accessory', array('accessory_id'=> $accessory->pivot->id, 'backto'=>'user')) }}" class="btn btn-primary btn-sm hidden-print">{{ trans('general.checkin') }}</a>
                      @endcan
                    </td>
                  </tr>
                  @endforeach
              </tbody>
            </table>
          </div>
        </div><!-- /accessories-tab -->

        <div class="tab-pane" id="consumables_tab">
          <div class="table-responsive">
            <table class="display table table-striped">
              <thead>
                <tr>
                  <th class="col-md-8">{{ trans('general.name') }}</th>
                  <th class="col-md-4">{{ trans('general.date') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($user->consumables as $consumable)
                <tr>
                  <td>{!! $consumable->present()->nameUrl() !!}</td>
                  <td>{{ $consumable->created_at }}</td>
                </tr>
                @endforeach
              </tbody>
          </table>
          </div>
        </div><!-- /consumables-tab -->

        <div class="tab-pane" id="files_tab">
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <p>{{ trans('admin/users/general.filetype_info') }}</p>
            </div>
            <div class="col-md-2">
              <!-- The fileinput-button span is used to style the file input field as button -->
              @can('update', $user)
              <span class="btn btn-info fileinput-button hidden-print">
                <i class="fa fa-plus icon-white"></i>
                <span>{{ trans('button.select_file') }}</span>
                <!-- The file input field used as target for the file upload widget -->
                <input id="fileupload" type="file" name="file[]" data-url="{{ route('upload/user', $user->id) }}">
              </span>
              @endcan
            </div>
            <div class="col-md-4">
              <input id="notes" type="text" name="notes">
            </div>
            <div class="col-md-6" id="progress-container" style="visibility: hidden; padding-bottom: 20px;">
              <!-- The global progress bar -->
              <div class="col-md-11">
                <div id="progress" class="progress progress-striped active" style="margin-top: 8px;">
                  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                      <span id="progress-bar-text">0% {{ trans('general.complete') }}</span>
                  </div>
                </div>
              </div>
              <div class="col-md-1">
                  <div class="pull-right progress-checkmark" style="display: none;">
                  </div>
              </div>
            </div>

            <div class="col-md-12 col-sm-12">
              <div class="table-responsive">
                <table id="files-table" class="display table table-striped">
                  <thead>
                    <tr>
                      <th class="col-md-5">{{ trans('general.notes') }}</th>
                      <th class="col-md-5"><span class="line"></span>{{ trans('general.file_name') }}</th>
                      <th class="col-md-2"></th>
                      <th class="col-md-2"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($user->uploads as $file)
                    <tr>
                      <td>
                        @if ($file->note)
                        {{ $file->note }}
                        @endif
                      </td>
                      <td>
                      {{ $file->filename }}
                      </td>
                      <td>
                        @if ($file->filename)
                        <a href="{{ route('show/userfile', [$user->id, $file->id]) }}" class="btn btn-default">{{ trans('general.download') }}</a>
                        @endif
                      </td>
                      <td>
                        @can('update', $user)
                        <a class="btn delete-asset btn-danger btn-sm hidden-print" href="{{ route('userfile.destroy', [$user->id, $file->id]) }}" data-content="Are you sure you wish to delete this file?" data-title="Delete {{ $file->filename }}?"><i class="fa fa-trash icon-white"></i></a>
                        @endcan
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div> <!--/ROW-->
        </div><!--/FILES_TAB-->

        <div class="tab-pane" id="history_tab">
          <div class="table-responsive">

            <table
                    data-click-to-select="true"
                    data-cookie-id-table="usersHistoryTable"
                    data-pagination="true"
                    data-id-table="usersHistoryTable"
                    data-search="true"
                    data-side-pagination="server"
                    data-show-columns="true"
                    data-show-export="true"
                    data-show-refresh="true"
                    data-sort-order="desc"
                    data-toolbar="#toolbar"
                    id="usersHistoryTable"
                    class="table table-striped snipe-table"
                    data-url="{{ route('api.activity.index', ['target_id' => $user->id, 'target_type' => 'user']) }}"
                    data-export-options='{
                "fileName": "export-{{ str_slug($user->present()->fullName ) }}-history-{{ date('Y-m-d') }}",
                "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
                }'>
              <thead>
              <tr>
                <th data-field="icon" style="width: 40px;" class="hidden-xs" data-formatter="iconFormatter"></th>
                <th class="col-sm-3" data-field="created_at" data-formatter="dateDisplayFormatter" data-sortable="true">{{ trans('general.date') }}</th>
                <th class="col-sm-2" data-field="admin" data-formatter="usersLinkObjFormatter">{{ trans('general.admin') }}</th>
                <th class="col-sm-2" data-field="action_type">{{ trans('general.action') }}</th>
                <th class="col-sm-3" data-field="item" data-formatter="polymorphicItemFormatter">{{ trans('general.item') }}</th>
                <th class="col-sm-2" data-field="target" data-formatter="polymorphicItemFormatter">{{ trans('general.target') }}</th>
              </tr>
              </thead>
            </table>

          </div>
        </div><!-- /.tab-pane -->

        <div class="tab-pane" id="managed_tab">
          <div class="table-responsive">
            <table class="display table table-striped">
              <thead>
                <tr>
                  <th class="col-md-8">{{ trans('general.name') }}</th>
                  <th class="col-md-4">{{ trans('general.date') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($user->managedLocations as $location)
                <tr>
                  <td>{!! $location->present()->nameUrl() !!}</td>
                  <td>{{ $location->created_at }}</td>
                </tr>
                @endforeach
              </tbody>
          </table>
          </div>
        </div><!-- /consumables-tab -->
      </div><!-- /.tab-content -->
    </div><!-- nav-tabs-custom -->
  </div>
</div>

@stop

@section('moar_scripts')
  @include ('partials.bootstrap-table', ['simple_view' => true])
<script nonce="{{ csrf_token() }}">
$(function () {

  $("#two_factor_reset").click(function(){
    $("#two_factor_resetrow").removeClass('success');
    $("#two_factor_resetrow").removeClass('danger');
    $("#two_factor_resetstatus").html('');
    $("#two_factor_reseticon").html('<i class="fa fa-spinner spin"></i>');
    $.ajax({
      url: '{{ route('api.users.two_factor_reset', ['id'=> $user->id]) }}',
      type: 'POST',
      data: {},
      headers: {
        "X-Requested-With": 'XMLHttpRequest',
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
      },
      dataType: 'json',

      success: function (data) {
        $("#two_factor_reset_toggle").html('').html('{{ trans('general.no') }}');
        $("#two_factor_reseticon").html('');
        $("#two_factor_resetstatus").html('<i class="fa fa-check text-success"></i>' + data.message);

      },

      error: function (data) {
        $("#two_factor_reseticon").html('');
        $("#two_factor_reseticon").html('<i class="fa fa-exclamation-triangle text-danger"></i>');
        $('#two_factor_resetstatus').text(data.message);
      }


    });
  });


    //binds to onchange event of your input field
    var uploadedFileSize = 0;
    $('#fileupload').bind('change', function() {
      uploadedFileSize = this.files[0].size;
      $('#progress-container').css('visibility', 'visible');
    });

    $('#fileupload').fileupload({
        //maxChunkSize: 100000,
        dataType: 'json',
        formData:{
        _token:'{{ csrf_token() }}',
        notes: $('#notes').val(),
        },

        progress: function (e, data) {
            //var overallProgress = $('#fileupload').fileupload('progress');
            //var activeUploads = $('#fileupload').fileupload('active');
            var progress = parseInt((data.loaded / uploadedFileSize) * 100, 10);
            $('.progress-bar').addClass('progress-bar-warning').css('width',progress + '%');
            $('#progress-bar-text').html(progress + '%');
            //console.dir(overallProgress);
        },

        done: function (e, data) {
            console.dir(data);
            // We use this instead of the fail option, since our API
            // returns a 200 OK status which always shows as "success"

            if (data && data.jqXHR && data.jqXHR.responseJSON && data.jqXHR.responseJSON.status === "error") {
                var errorMessage = data.jqXHR.responseJSON.messages["file.0"];
                $('#progress-bar-text').html(errorMessage[0]);
                $('.progress-bar').removeClass('progress-bar-warning').addClass('progress-bar-danger').css('width','100%');
                $('.progress-checkmark').fadeIn('fast').html('<i class="fa fa-times fa-3x icon-white" style="color: #d9534f"></i>');
            } else {
                $('.progress-bar').removeClass('progress-bar-warning').addClass('progress-bar-success').css('width','100%');
                $('.progress-checkmark').fadeIn('fast');
                $('#progress-container').delay(950).css('visibility', 'visible');
                $('.progress-bar-text').html('Finished!');
                $('.progress-checkmark').fadeIn('fast').html('<i class="fa fa-check fa-3x icon-white" style="color: green"></i>');
                $.each(data.result, function (index, file) {
                    $('<tr><td>' + file.note + '</td><<td>' + file.filename + '</td></tr>').prependTo("#files-table > tbody");
                });
            }
            $('#progress').removeClass('active');


        }
    });
});
</script>


@stop
