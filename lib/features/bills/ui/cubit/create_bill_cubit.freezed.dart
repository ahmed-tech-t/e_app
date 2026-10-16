// GENERATED CODE - DO NOT MODIFY BY HAND
// coverage:ignore-file
// ignore_for_file: type=lint, type=warning, deprecated_member_use, deprecated_member_use_from_same_package
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'create_bill_cubit.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

// GENERATED CODE - DO NOT MODIFY BY HAND
// dart format off
T _$identity<T>(T value) => value;
/// @nodoc
mixin _$CreateBillState {

 String get customerName; String get customerPhone; PriceType get priceType; double get discount; List<BillCartItem> get items; bool get isPreviewLoading; bool get isSubmitting; Bill? get serverPreview; String? get errorMessage;
/// Create a copy of CreateBillState
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$CreateBillStateCopyWith<CreateBillState> get copyWith => _$CreateBillStateCopyWithImpl<CreateBillState>(this as CreateBillState, _$identity);



@override
bool operator ==(Object other) {
  final _this = this as CreateBillState;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is CreateBillState&&(identical(other.customerName, _this.customerName) || other.customerName == _this.customerName)&&(identical(other.customerPhone, _this.customerPhone) || other.customerPhone == _this.customerPhone)&&(identical(other.priceType, _this.priceType) || other.priceType == _this.priceType)&&(identical(other.discount, _this.discount) || other.discount == _this.discount)&&const DeepCollectionEquality().equals(other.items, _this.items)&&(identical(other.isPreviewLoading, _this.isPreviewLoading) || other.isPreviewLoading == _this.isPreviewLoading)&&(identical(other.isSubmitting, _this.isSubmitting) || other.isSubmitting == _this.isSubmitting)&&(identical(other.serverPreview, _this.serverPreview) || other.serverPreview == _this.serverPreview)&&(identical(other.errorMessage, _this.errorMessage) || other.errorMessage == _this.errorMessage));
}


@override
int get hashCode {
  final _this = this as CreateBillState;
  return Object.hash(runtimeType,_this.customerName,_this.customerPhone,_this.priceType,_this.discount,const DeepCollectionEquality().hash(_this.items),_this.isPreviewLoading,_this.isSubmitting,_this.serverPreview,_this.errorMessage);
}

@override
String toString() {
  final _this = this as CreateBillState;
  return 'CreateBillState(customerName: ${_this.customerName}, customerPhone: ${_this.customerPhone}, priceType: ${_this.priceType}, discount: ${_this.discount}, items: ${_this.items}, isPreviewLoading: ${_this.isPreviewLoading}, isSubmitting: ${_this.isSubmitting}, serverPreview: ${_this.serverPreview}, errorMessage: ${_this.errorMessage})';
}


}

/// @nodoc
abstract mixin class $CreateBillStateCopyWith<$Res>  {
  factory $CreateBillStateCopyWith(CreateBillState value, $Res Function(CreateBillState) _then) = _$CreateBillStateCopyWithImpl;
@useResult
$Res call({
 String customerName, String customerPhone, PriceType priceType, double discount, List<BillCartItem> items, bool isPreviewLoading, bool isSubmitting, Bill? serverPreview, String? errorMessage
});


$BillCopyWith<$Res>? get serverPreview;

}
/// @nodoc
class _$CreateBillStateCopyWithImpl<$Res>
    implements $CreateBillStateCopyWith<$Res> {
  _$CreateBillStateCopyWithImpl(this._self, this._then);

  final CreateBillState _self;
  final $Res Function(CreateBillState) _then;

/// Create a copy of CreateBillState
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? customerName = null,Object? customerPhone = null,Object? priceType = null,Object? discount = null,Object? items = null,Object? isPreviewLoading = null,Object? isSubmitting = null,Object? serverPreview = freezed,Object? errorMessage = freezed,}) {
  return _then(CreateBillState(
customerName: null == customerName ? _self.customerName : customerName // ignore: cast_nullable_to_non_nullable
as String,customerPhone: null == customerPhone ? _self.customerPhone : customerPhone // ignore: cast_nullable_to_non_nullable
as String,priceType: null == priceType ? _self.priceType : priceType // ignore: cast_nullable_to_non_nullable
as PriceType,discount: null == discount ? _self.discount : discount // ignore: cast_nullable_to_non_nullable
as double,items: null == items ? _self.items : items // ignore: cast_nullable_to_non_nullable
as List<BillCartItem>,isPreviewLoading: null == isPreviewLoading ? _self.isPreviewLoading : isPreviewLoading // ignore: cast_nullable_to_non_nullable
as bool,isSubmitting: null == isSubmitting ? _self.isSubmitting : isSubmitting // ignore: cast_nullable_to_non_nullable
as bool,serverPreview: freezed == serverPreview ? _self.serverPreview : serverPreview // ignore: cast_nullable_to_non_nullable
as Bill?,errorMessage: freezed == errorMessage ? _self.errorMessage : errorMessage // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}
/// Create a copy of CreateBillState
/// with the given fields replaced by the non-null parameter values.
@override
@pragma('vm:prefer-inline')
$BillCopyWith<$Res>? get serverPreview {
    if (_self.serverPreview == null) {
    return null;
  }

  return $BillCopyWith<$Res>(_self.serverPreview!, (value) {
    return _then(_self.copyWith(serverPreview: value));
  });
}
}


/// Adds pattern-matching-related methods to [CreateBillState].
extension CreateBillStatePatterns on CreateBillState {
/// A variant of `map` that fallback to returning `orElse`.
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case final Subclass value:
///     return ...;
///   case _:
///     return orElse();
/// }
/// ```

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _CreateBillState value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _CreateBillState() when $default != null:
return $default(_that);case _:
  return orElse();

}
}
/// A `switch`-like method, using callbacks.
///
/// Callbacks receives the raw object, upcasted.
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case final Subclass value:
///     return ...;
///   case final Subclass2 value:
///     return ...;
/// }
/// ```

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _CreateBillState value)  $default,){
final _that = this;
switch (_that) {
case _CreateBillState():
return $default(_that);case _:
  throw StateError('Unexpected subclass');

}
}
/// A variant of `map` that fallback to returning `null`.
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case final Subclass value:
///     return ...;
///   case _:
///     return null;
/// }
/// ```

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _CreateBillState value)?  $default,){
final _that = this;
switch (_that) {
case _CreateBillState() when $default != null:
return $default(_that);case _:
  return null;

}
}
/// A variant of `when` that fallback to an `orElse` callback.
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case Subclass(:final field):
///     return ...;
///   case _:
///     return orElse();
/// }
/// ```

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( String customerName,  String customerPhone,  PriceType priceType,  double discount,  List<BillCartItem> items,  bool isPreviewLoading,  bool isSubmitting,  Bill? serverPreview,  String? errorMessage)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _CreateBillState() when $default != null:
return $default(_that.customerName,_that.customerPhone,_that.priceType,_that.discount,_that.items,_that.isPreviewLoading,_that.isSubmitting,_that.serverPreview,_that.errorMessage);case _:
  return orElse();

}
}
/// A `switch`-like method, using callbacks.
///
/// As opposed to `map`, this offers destructuring.
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case Subclass(:final field):
///     return ...;
///   case Subclass2(:final field2):
///     return ...;
/// }
/// ```

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( String customerName,  String customerPhone,  PriceType priceType,  double discount,  List<BillCartItem> items,  bool isPreviewLoading,  bool isSubmitting,  Bill? serverPreview,  String? errorMessage)  $default,) {final _that = this;
switch (_that) {
case _CreateBillState():
return $default(_that.customerName,_that.customerPhone,_that.priceType,_that.discount,_that.items,_that.isPreviewLoading,_that.isSubmitting,_that.serverPreview,_that.errorMessage);case _:
  throw StateError('Unexpected subclass');

}
}
/// A variant of `when` that fallback to returning `null`
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case Subclass(:final field):
///     return ...;
///   case _:
///     return null;
/// }
/// ```

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( String customerName,  String customerPhone,  PriceType priceType,  double discount,  List<BillCartItem> items,  bool isPreviewLoading,  bool isSubmitting,  Bill? serverPreview,  String? errorMessage)?  $default,) {final _that = this;
switch (_that) {
case _CreateBillState() when $default != null:
return $default(_that.customerName,_that.customerPhone,_that.priceType,_that.discount,_that.items,_that.isPreviewLoading,_that.isSubmitting,_that.serverPreview,_that.errorMessage);case _:
  return null;

}
}

}

/// @nodoc


class _CreateBillState implements CreateBillState {
  const _CreateBillState({this.customerName = '', this.customerPhone = '', this.priceType = PriceType.retail, this.discount = 0.0,  List<BillCartItem> items = const [], this.isPreviewLoading = false, this.isSubmitting = false, this.serverPreview, this.errorMessage}): _items = items;
  

@override@JsonKey() final  String customerName;
@override@JsonKey() final  String customerPhone;
@override@JsonKey() final  PriceType priceType;
@override@JsonKey() final  double discount;
 final  List<BillCartItem> _items;
@override@JsonKey() List<BillCartItem> get items {
  if (_items is EqualUnmodifiableListView) return _items;
  // ignore: implicit_dynamic_type
  return EqualUnmodifiableListView(_items);
}

@override@JsonKey() final  bool isPreviewLoading;
@override@JsonKey() final  bool isSubmitting;
@override final  Bill? serverPreview;
@override final  String? errorMessage;

/// Create a copy of CreateBillState
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$CreateBillStateCopyWith<_CreateBillState> get copyWith => __$CreateBillStateCopyWithImpl<_CreateBillState>(this, _$identity);



@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _CreateBillState&&(identical(other.customerName, customerName) || other.customerName == customerName)&&(identical(other.customerPhone, customerPhone) || other.customerPhone == customerPhone)&&(identical(other.priceType, priceType) || other.priceType == priceType)&&(identical(other.discount, discount) || other.discount == discount)&&const DeepCollectionEquality().equals(other.items, _items)&&(identical(other.isPreviewLoading, isPreviewLoading) || other.isPreviewLoading == isPreviewLoading)&&(identical(other.isSubmitting, isSubmitting) || other.isSubmitting == isSubmitting)&&(identical(other.serverPreview, serverPreview) || other.serverPreview == serverPreview)&&(identical(other.errorMessage, errorMessage) || other.errorMessage == errorMessage));
}


@override
int get hashCode {
    return Object.hash(runtimeType,customerName,customerPhone,priceType,discount,const DeepCollectionEquality().hash(_items),isPreviewLoading,isSubmitting,serverPreview,errorMessage);
}

@override
String toString() {
    return 'CreateBillState(customerName: $customerName, customerPhone: $customerPhone, priceType: $priceType, discount: $discount, items: $items, isPreviewLoading: $isPreviewLoading, isSubmitting: $isSubmitting, serverPreview: $serverPreview, errorMessage: $errorMessage)';
}


}

/// @nodoc
abstract mixin class _$CreateBillStateCopyWith<$Res> implements $CreateBillStateCopyWith<$Res> {
  factory _$CreateBillStateCopyWith(_CreateBillState value, $Res Function(_CreateBillState) _then) = __$CreateBillStateCopyWithImpl;
@override @useResult
$Res call({
 String customerName, String customerPhone, PriceType priceType, double discount, List<BillCartItem> items, bool isPreviewLoading, bool isSubmitting, Bill? serverPreview, String? errorMessage
});


@override $BillCopyWith<$Res>? get serverPreview;

}
/// @nodoc
class __$CreateBillStateCopyWithImpl<$Res>
    implements _$CreateBillStateCopyWith<$Res> {
  __$CreateBillStateCopyWithImpl(this._self, this._then);

  final _CreateBillState _self;
  final $Res Function(_CreateBillState) _then;

/// Create a copy of CreateBillState
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? customerName = null,Object? customerPhone = null,Object? priceType = null,Object? discount = null,Object? items = null,Object? isPreviewLoading = null,Object? isSubmitting = null,Object? serverPreview = freezed,Object? errorMessage = freezed,}) {
  return _then(_CreateBillState(
customerName: null == customerName ? _self.customerName : customerName // ignore: cast_nullable_to_non_nullable
as String,customerPhone: null == customerPhone ? _self.customerPhone : customerPhone // ignore: cast_nullable_to_non_nullable
as String,priceType: null == priceType ? _self.priceType : priceType // ignore: cast_nullable_to_non_nullable
as PriceType,discount: null == discount ? _self.discount : discount // ignore: cast_nullable_to_non_nullable
as double,items: null == items ? _self._items : items // ignore: cast_nullable_to_non_nullable
as List<BillCartItem>,isPreviewLoading: null == isPreviewLoading ? _self.isPreviewLoading : isPreviewLoading // ignore: cast_nullable_to_non_nullable
as bool,isSubmitting: null == isSubmitting ? _self.isSubmitting : isSubmitting // ignore: cast_nullable_to_non_nullable
as bool,serverPreview: freezed == serverPreview ? _self.serverPreview : serverPreview // ignore: cast_nullable_to_non_nullable
as Bill?,errorMessage: freezed == errorMessage ? _self.errorMessage : errorMessage // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}

/// Create a copy of CreateBillState
/// with the given fields replaced by the non-null parameter values.
@override
@pragma('vm:prefer-inline')
$BillCopyWith<$Res>? get serverPreview {
    if (_self.serverPreview == null) {
    return null;
  }

  return $BillCopyWith<$Res>(_self.serverPreview!, (value) {
    return _then(_self.copyWith(serverPreview: value));
  });
}
}

// dart format on
