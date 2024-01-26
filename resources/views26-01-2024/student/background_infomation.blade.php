<div id="step-5">
  <form method="POST" action="{{ route('background')}}" id="background">
    @csrf
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <p><strong>Disclaimer:</strong> Providing false information about the immigration history, will result in application rejection by the university</p>
              <div class="col-md-6 text-right">
                Have you ever applied for Visa ?

              </div>
              <div class="col-md-6">
                <div class="form-check-inline">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input type"  name="applied_visa" value="1" @if ($appliedVisa == 1)
                        checked
                    @endif>Yes
                  </label>
                </div>
                <div class="form-check-inline">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input type" name="applied_visa" value="0" name="applied_visa" @if ($appliedVisa == 0)
                    checked
                @endif>No
                  </label>
                </div>
                @error('applied_visa')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
                {{-- <div class="form-group" id="country-container-type">
                  <select class="form-control country_id @error('applied_visa_country') {{ errCls() }} @enderror" name="applied_visa_country">
                    @isset($appliedVisaCountry[0])
                      <option value="{{ $appliedVisaCountry[0]->id }}">{{ $appliedVisaCountry[0]->name }}</option>
                    @endisset
                  </select>
                </div> --}}
                @error('applied_visa_country')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
          </div>
          <div class="row" style="margin-top: 1%">
            <div class="col-md-6 text-right">
              Any Visa Refusal ?
            </div>
            <div class="col-md-6">
              <div class="form-check-inline">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input refusal" name="visa_refusal" value = "1" @if ($refuseVisa == 1)
                    checked
                   @endif>Yes
                </label>
              </div>
              <div class="form-check-inline">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input refusal" name="visa_refusal" value = "0" @if($refuseVisa == 0)
                  checked
                 @endif>No
                </label>
              </div>

              <div id="country-container-refusal">
                <div class="form-group">
                  {{-- <select class="form-control country_id @error('visa_refusal_country') {{ errCls() }} @enderror"  name="visa_refusal_country">
                    @isset($refuseCountry[0])
                     <option value="{{ $refuseCountry[0]->id }}">{{ $refuseCountry[0]->name }}</option>
                    @endisset
                  </select> --}}
                </div>
                <div class="form-group">
                   <textarea name="visa_refusal_details"  class="form-control @error('visa_refusal_type') {{ errCls() }} @enderror" cols="10" rows="5" placeholder="Please provide more details here">{{ old('visa_refusal_type', $refuseVisaType)}}</textarea>
                </div>
            </div>
            @error('visa_refusal_country')
            <p class="text-danger">{{ $message }}</p>
           @enderror
           @error('visa_refusal_details')
             <p class="text-danger">{{ $message }}</p>
           @enderror
            </div>
        </div>
        </div>
      </div>
      <div class="row mt-2">
      <div class="col-md-12">
        <button type="button" class="btn btn-primary previous" >Previous</button>
      <button type="submit" class="btn btn-primary float-right" id="background-btn">Next</button>
      </div>
    </div>
  </form>
</div>
