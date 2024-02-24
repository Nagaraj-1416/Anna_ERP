<script>
    function statusLabelColor($status) {
        var $color = 'text-muted';
        if ($status === 'Draft') {
            $color = 'text-info';
        } else if ($status === 'Awaiting Approval') {
            $color = 'text-warning';
        } else if ($status === 'Open') {
            $color = 'text-info';
        } else if ($status === 'Overdue') {
            $color = 'text-danger';
        } else if ($status === 'Partially Paid') {
            $color = 'text-warning';
        } else if ($status === 'Paid') {
            $color = 'text-green';
        } else if ($status === 'Canceled') {
            $color = 'text-danger';
        } else if ($status === 'Refunded') {
            $color = 'text-danger';
        } else if ($status === 'Pending') {
            $color = 'text-warning';
        } else if ($status === 'Partially Billed') {
            $color = 'text-warning';
        } else if ($status === 'Sent') {
            $color = 'text-warning';
        } else if ($status === 'Accepted') {
            $color = 'text-green';
        } else if ($status === 'Declined') {
            $color = 'text-danger';
        } else if ($status === 'Ordered') {
            $color = 'text-green';
        } else if ($status === 'Converted to Estimate') {
            $color = 'text-green';
        } else if ($status === 'Converted to Order') {
            $color = 'text-green';
        } else if ($status === 'Closed') {
            $color = 'text-green';
        } else if ($status === 'Invoiced') {
            $color = 'text-green';
        } else if ($status === 'Unreported') {
            $color = 'text-warning';
        } else if ($status === 'Unsubmitted') {
            $color = 'text-warning';
        } else if ($status === 'Submitted') {
            $color = 'text-info';
        } else if ($status === 'Approved') {
            $color = 'text-green';
        } else if ($status === 'Rejected') {
            $color = 'text-danger';
        } else if ($status === 'Reimbursed') {
            $color = 'text-green';
        } else if ($status === 'Billed') {
            $color = 'text-green';
        } else if ($status === 'Partially Invoiced') {
            $color = 'text-warning';
        } else if ($status === 'Confirmed') {
            $color = 'text-green';
        } else if ($status === 'Completed') {
            $color = 'text-green';
        } else if ($status === 'Progress') {
            $color = 'text-warning';
        } else if ($status === 'Active') {
            $color = 'text-info';
        }else if($status === 'Cash'){
            $color = 'text-green';
        }else if($status === 'Credit'){
            $color = 'text-danger';
        }
        return $color;
    }

    function sum(array) {
        return _.reduce(array, function (memo, num) {
            return memo + num;
        }, 0);
    }

    function soOutstanding(order) {
        var $outstanding = [];

        var $invoices = order.invoices;
        var $payments = order.payments;

        var $invoiced = sum(_.pluck($invoices, 'amount'));
        var $paidAmount = sum(_.pluck($payments, 'payment'));

        $outstanding['invoiced'] = $invoiced;
        $outstanding['paid'] = $paidAmount;
        $outstanding['balance'] = (order.total - $paidAmount);

        return $outstanding;
    }

    function invOutstanding($invoice) {
        var $outstanding = [];
        var $payments = $invoice.payments;
        var $paidAmount = sum(_.pluck($payments, 'payment'));
        $outstanding['paid'] = $paidAmount;
        $outstanding['balance'] = ($invoice.amount - $paidAmount);
        return $outstanding;
    }

    function getCustomerCreditLimit($credit) {
        var $totalRefunds = getCustomerCreditUsed($credit);
        return $credit.amount - $totalRefunds;
    }

    function getCustomerCreditUsed($credit) {
        var $totalRefunds = sum(_.pluck($credit.refunds, 'amount'));
        var $totalInvoiced = sum(_.pluck($credit.payments, 'payment'));
        return $totalRefunds + $totalInvoiced;
    }

    function poOutstanding(order) {
        var $outstanding = [];
        var $invoices = order.bills;
        var $payments = order.payments;
        var $invoiced = sum(_.pluck($invoices, 'amount'));
        var $paidAmount = sum(_.pluck($payments, 'payment'));
        $outstanding['billed'] = $invoiced;
        $outstanding['paid'] = $paidAmount;
        $outstanding['balance'] = (order.total - $paidAmount);
        return $outstanding;
    }

    function billOutstanding($bill) {
        var $outstanding = [];
        var $payments = $bill.payments;
        var $paidAmount = sum(_.pluck($payments, 'payment'));
        $outstanding['paid'] = $paidAmount;
        $outstanding['balance'] = ($bill.amount - $paidAmount);
        return $outstanding;
    }

    function getSupplierCreditUsed($credit) {
        var $totalRefunds = sum(_.pluck($credit.refunds, 'amount'));
        var $totalBilled = sum(_.pluck($credit.payments, 'payment'));
        return $totalRefunds + $totalBilled;
    }

    function getSupplierCreditLimit($credit) {
        $totalRefunds = getSupplierCreditUsed($credit);
        return $credit.amount - $totalRefunds;
    }
</script>