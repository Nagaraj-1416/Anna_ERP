@extends('layouts.master')
@section('title', 'Stock review Details')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Stock') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Payment Details</h4>
                </div>
                <div class="card-body">
                    <!-- action buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @if($review->status == 'Drafted')
                                <button class="btn btn-success btn-sm review-approve">
                                    <i class="fa fa-check"></i> Approve Review
                                </button>
                                <button class="btn btn-danger btn-sm review-delete">
                                    <i class="fa fa-remove"></i> Delete Review
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- estimate summary and history -->
                    <div class="row custom-top-margin">
                        <div class="col-md-9">
                            <div class="card card-body">
                                <h3>
                                    <b>{{ $review->date }}</b> |
                                    <span class="{{ statusLabelColor($review->status) }}">
                                        {{ $review->status }}
                                    </span>
                                    <span class="pull-right">#{{ $review->staff->short_name }}</span></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Store :</b> {{ $review->store->name }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><b>Company :</b> {{ $review->company->name or 'None' }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><b>Prepared by :</b> {{ $review->preparedBy->name or 'None' }}</p>
                                        <p><b>Prepared on :</b> {{ $review->prepared_on or 'None' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><b>Approved by :</b> {{ $review->approvedBy->name or 'None' }}</p>
                                        <p><b>Approved on :</b> {{ $review->approved_on or 'None' }}</p>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h6><b>ITEMS</b></h6>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                <tr>
                                                    <th>Item Details</th>
                                                    <th width="10%" class="text-center">Available</th>
                                                    <th width="10%" class="text-center">Actual</th>
                                                    <th width="10%" class="text-center">Excess</th>
                                                    <th width="10%" class="text-center">Shortage</th>
                                                    <th width="10%" class="text-right">Rate</th>
                                                    <th width="10%" class="text-right">Excess Amount</th>
                                                    <th width="10%" class="text-right">Shortage Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($review->items as $reviewItem)
                                                    <tr>
                                                        <td>
                                                            {{ $reviewItem->product->name or 'None' }}<br />
                                                            <a target="_blank" href="{{ route('stock.show', $reviewItem->stock) }}">View Stock</a>
                                                            | <a target="_blank" href="{{ route('setting.product.show', $reviewItem->product) }}">View Product</a>
                                                        </td>
                                                        <td class="text-center">{{ $reviewItem->available_qty }}</td>
                                                        <td class="text-center">{{ $reviewItem->actual_qty }}</td>
                                                        <td class="text-center">{{ $reviewItem->excess_qty }}</td>
                                                        <td class="text-center">{{ $reviewItem->shortage_qty }}</td>
                                                        <td class="text-right">{{ $reviewItem->rate }}</td>
                                                        <td class="text-right">{{ $reviewItem->excess_amount }}</td>
                                                        <td class="text-right" >{{ $reviewItem->shortage_amount }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                @if($review->notes)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="order-notes">
                                                <h5>Notes</h5>
                                                <small class="text-muted">{{ $review->notes or 'None' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <!-- recent comments -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.comment.index', ['model' => $review])
                                </div>
                            </div>

                            <!-- recent audit logs -->
                            <div class="card">
                                <div class="card-body">
                                    @include('general.log.index', ['model' => $review, 'modelName' => 'Stock review'])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('general.comment.script', ['modelId' => $review->id])
    @include('_inc.document.script', ['model' => $review])
    <script>
        $(document).ready(function () {
            var $deleteBtn = $('.review-delete');
            var $approveBtn = $('.review-approve');

            var deleteRoute = '{{ route('stock.review.delete', $review) }}';
            var approveRoute = '{{ route('stock.review.approve', $review) }}';
            var indexRoute = '{{ route('stock.review.index') }}';
            var showRoute = '{{ route('stock.review.show', $review) }}';

            $deleteBtn.click(function (e) {
                e.preventDefault();
                Swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this transaction!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor : '#fc4b6c',
                    confirmButtonText: 'Yes, Delete it!',
                    cancelButtonText: 'No, Keep it'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: deleteRoute,
                            type: 'DELETE',
                            data: {_token : '{{ csrf_token() }}'},
                            success: function(response) {
                                if (response.success){
                                    Swal(
                                        'Deleted!',
                                        'Stock review details deleted successfully.',
                                        'success'
                                    );
                                    setTimeout(function () {
                                        window.location.href = indexRoute;
                                    }, 2000);
                                }else{
                                    Swal(
                                        'Failed!',
                                        'Your request is failed.',
                                        'error'
                                    )
                                }
                            }
                        });
                    }
                })
            });

            $approveBtn.click(function (e) {
                e.preventDefault();
                Swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this transaction!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor : '#087a15',
                    confirmButtonText: 'Yes, Approve it!',
                    cancelButtonText: 'Close'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: approveRoute,
                            type: 'PATCH',
                            data: {_token : '{{ csrf_token() }}'},
                            success: function(response) {
                                if (response.success){
                                    Swal(
                                        'Approved!',
                                        'Stock review details approved successfully.',
                                        'success'
                                    );
                                    setTimeout(function () {
                                        window.location.href = showRoute;
                                    }, 2000);
                                }else{
                                    Swal(
                                        'Failed!',
                                        'Your request is failed.',
                                        'error'
                                    )
                                }
                            }
                        });
                    }
                })
            });
        });
    </script>
@endsection
