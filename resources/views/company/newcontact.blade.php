@php $randomnumber = time().rand(); @endphp
<div class="row mt-2">
    <input type="hidden" name="contact[{{ $randomnumber }}][contactid]" value="">
    <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="contact[{{ $randomnumber }}][contact_name]" class="form-control contact_name "
                maxlength="100" value="" placeholder="Contact Name*" required>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="contact[{{ $randomnumber }}][contact_title]" class="form-control contact_title "
                maxlength="100" value="" placeholder="Contact Title*" required>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <input type="email" name="contact[{{ $randomnumber }}][contact_email]" class="form-control contact_email"
                value="" placeholder="Contact Email*" required>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <input type="tel" name="contact[{{ $randomnumber }}][contact_number]"
                class="form-control contact_number" maxlength="{{ config('params.max_mobile') }}" value=""
                placeholder="Contact No.">
            <input type="hidden" class="country_code" name="contact[{{ $randomnumber }}][country_code]">
        </div>
    </div>

    <div class="col-md-1">
        <div class="form-group ">
            <a class="remove_button_comp" title="Remove Contact"><i
                    class="fas fa-minus-circle removeiconclass text-danger" aria-hidden="true"></i></a>
        </div>
    </div>
</div>
