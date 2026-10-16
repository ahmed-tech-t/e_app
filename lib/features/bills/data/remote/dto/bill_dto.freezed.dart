// GENERATED CODE - DO NOT MODIFY BY HAND
// coverage:ignore-file
// ignore_for_file: type=lint, type=warning, deprecated_member_use, deprecated_member_use_from_same_package
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'bill_dto.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

// GENERATED CODE - DO NOT MODIFY BY HAND
// dart format off
T _$identity<T>(T value) => value;

/// @nodoc
mixin _$BillDto {

 int? get id; String? get code;@JsonKey(name: 'customer_name') String? get customerName;@JsonKey(name: 'customer_phone') String? get customerPhone; double? get total; double? get discount; double? get tax;@JsonKey(name: 'grand_total') double? get grandTotal; List<SaleItemDto> get items;
/// Create a copy of BillDto
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$BillDtoCopyWith<BillDto> get copyWith => _$BillDtoCopyWithImpl<BillDto>(this as BillDto, _$identity);

  /// Serializes this BillDto to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as BillDto;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is BillDto&&(identical(other.id, _this.id) || other.id == _this.id)&&(identical(other.code, _this.code) || other.code == _this.code)&&(identical(other.customerName, _this.customerName) || other.customerName == _this.customerName)&&(identical(other.customerPhone, _this.customerPhone) || other.customerPhone == _this.customerPhone)&&(identical(other.total, _this.total) || other.total == _this.total)&&(identical(other.discount, _this.discount) || other.discount == _this.discount)&&(identical(other.tax, _this.tax) || other.tax == _this.tax)&&(identical(other.grandTotal, _this.grandTotal) || other.grandTotal == _this.grandTotal)&&const DeepCollectionEquality().equals(other.items, _this.items));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as BillDto;
  return Object.hash(runtimeType,_this.id,_this.code,_this.customerName,_this.customerPhone,_this.total,_this.discount,_this.tax,_this.grandTotal,const DeepCollectionEquality().hash(_this.items));
}

@override
String toString() {
  final _this = this as BillDto;
  return 'BillDto(id: ${_this.id}, code: ${_this.code}, customerName: ${_this.customerName}, customerPhone: ${_this.customerPhone}, total: ${_this.total}, discount: ${_this.discount}, tax: ${_this.tax}, grandTotal: ${_this.grandTotal}, items: ${_this.items})';
}


}

/// @nodoc
abstract mixin class $BillDtoCopyWith<$Res>  {
  factory $BillDtoCopyWith(BillDto value, $Res Function(BillDto) _then) = _$BillDtoCopyWithImpl;
@useResult
$Res call({
 int? id, String? code,@JsonKey(name: 'customer_name') String? customerName,@JsonKey(name: 'customer_phone') String? customerPhone, double? total, double? discount, double? tax,@JsonKey(name: 'grand_total') double? grandTotal, List<SaleItemDto> items
});




}
/// @nodoc
class _$BillDtoCopyWithImpl<$Res>
    implements $BillDtoCopyWith<$Res> {
  _$BillDtoCopyWithImpl(this._self, this._then);

  final BillDto _self;
  final $Res Function(BillDto) _then;

/// Create a copy of BillDto
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? id = freezed,Object? code = freezed,Object? customerName = freezed,Object? customerPhone = freezed,Object? total = freezed,Object? discount = freezed,Object? tax = freezed,Object? grandTotal = freezed,Object? items = null,}) {
  return _then(BillDto(
id: freezed == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int?,code: freezed == code ? _self.code : code // ignore: cast_nullable_to_non_nullable
as String?,customerName: freezed == customerName ? _self.customerName : customerName // ignore: cast_nullable_to_non_nullable
as String?,customerPhone: freezed == customerPhone ? _self.customerPhone : customerPhone // ignore: cast_nullable_to_non_nullable
as String?,total: freezed == total ? _self.total : total // ignore: cast_nullable_to_non_nullable
as double?,discount: freezed == discount ? _self.discount : discount // ignore: cast_nullable_to_non_nullable
as double?,tax: freezed == tax ? _self.tax : tax // ignore: cast_nullable_to_non_nullable
as double?,grandTotal: freezed == grandTotal ? _self.grandTotal : grandTotal // ignore: cast_nullable_to_non_nullable
as double?,items: null == items ? _self.items : items // ignore: cast_nullable_to_non_nullable
as List<SaleItemDto>,
  ));
}

}


/// Adds pattern-matching-related methods to [BillDto].
extension BillDtoPatterns on BillDto {
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

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _BillDto value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _BillDto() when $default != null:
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

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _BillDto value)  $default,){
final _that = this;
switch (_that) {
case _BillDto():
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

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _BillDto value)?  $default,){
final _that = this;
switch (_that) {
case _BillDto() when $default != null:
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

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( int? id,  String? code, @JsonKey(name: 'customer_name')  String? customerName, @JsonKey(name: 'customer_phone')  String? customerPhone,  double? total,  double? discount,  double? tax, @JsonKey(name: 'grand_total')  double? grandTotal,  List<SaleItemDto> items)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _BillDto() when $default != null:
return $default(_that.id,_that.code,_that.customerName,_that.customerPhone,_that.total,_that.discount,_that.tax,_that.grandTotal,_that.items);case _:
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

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( int? id,  String? code, @JsonKey(name: 'customer_name')  String? customerName, @JsonKey(name: 'customer_phone')  String? customerPhone,  double? total,  double? discount,  double? tax, @JsonKey(name: 'grand_total')  double? grandTotal,  List<SaleItemDto> items)  $default,) {final _that = this;
switch (_that) {
case _BillDto():
return $default(_that.id,_that.code,_that.customerName,_that.customerPhone,_that.total,_that.discount,_that.tax,_that.grandTotal,_that.items);case _:
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

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( int? id,  String? code, @JsonKey(name: 'customer_name')  String? customerName, @JsonKey(name: 'customer_phone')  String? customerPhone,  double? total,  double? discount,  double? tax, @JsonKey(name: 'grand_total')  double? grandTotal,  List<SaleItemDto> items)?  $default,) {final _that = this;
switch (_that) {
case _BillDto() when $default != null:
return $default(_that.id,_that.code,_that.customerName,_that.customerPhone,_that.total,_that.discount,_that.tax,_that.grandTotal,_that.items);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _BillDto implements BillDto {
  const _BillDto({this.id, this.code, @JsonKey(name: 'customer_name') this.customerName, @JsonKey(name: 'customer_phone') this.customerPhone, this.total, this.discount, this.tax, @JsonKey(name: 'grand_total') this.grandTotal,  List<SaleItemDto> items = const []}): _items = items;
  factory _BillDto.fromJson(Map<String, dynamic> json) => _$BillDtoFromJson(json);

@override final  int? id;
@override final  String? code;
@override@JsonKey(name: 'customer_name') final  String? customerName;
@override@JsonKey(name: 'customer_phone') final  String? customerPhone;
@override final  double? total;
@override final  double? discount;
@override final  double? tax;
@override@JsonKey(name: 'grand_total') final  double? grandTotal;
 final  List<SaleItemDto> _items;
@override@JsonKey() List<SaleItemDto> get items {
  if (_items is EqualUnmodifiableListView) return _items;
  // ignore: implicit_dynamic_type
  return EqualUnmodifiableListView(_items);
}


/// Create a copy of BillDto
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$BillDtoCopyWith<_BillDto> get copyWith => __$BillDtoCopyWithImpl<_BillDto>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$BillDtoToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _BillDto&&(identical(other.id, id) || other.id == id)&&(identical(other.code, code) || other.code == code)&&(identical(other.customerName, customerName) || other.customerName == customerName)&&(identical(other.customerPhone, customerPhone) || other.customerPhone == customerPhone)&&(identical(other.total, total) || other.total == total)&&(identical(other.discount, discount) || other.discount == discount)&&(identical(other.tax, tax) || other.tax == tax)&&(identical(other.grandTotal, grandTotal) || other.grandTotal == grandTotal)&&const DeepCollectionEquality().equals(other.items, _items));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hash(runtimeType,id,code,customerName,customerPhone,total,discount,tax,grandTotal,const DeepCollectionEquality().hash(_items));
}

@override
String toString() {
    return 'BillDto(id: $id, code: $code, customerName: $customerName, customerPhone: $customerPhone, total: $total, discount: $discount, tax: $tax, grandTotal: $grandTotal, items: $items)';
}


}

/// @nodoc
abstract mixin class _$BillDtoCopyWith<$Res> implements $BillDtoCopyWith<$Res> {
  factory _$BillDtoCopyWith(_BillDto value, $Res Function(_BillDto) _then) = __$BillDtoCopyWithImpl;
@override @useResult
$Res call({
 int? id, String? code,@JsonKey(name: 'customer_name') String? customerName,@JsonKey(name: 'customer_phone') String? customerPhone, double? total, double? discount, double? tax,@JsonKey(name: 'grand_total') double? grandTotal, List<SaleItemDto> items
});




}
/// @nodoc
class __$BillDtoCopyWithImpl<$Res>
    implements _$BillDtoCopyWith<$Res> {
  __$BillDtoCopyWithImpl(this._self, this._then);

  final _BillDto _self;
  final $Res Function(_BillDto) _then;

/// Create a copy of BillDto
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? id = freezed,Object? code = freezed,Object? customerName = freezed,Object? customerPhone = freezed,Object? total = freezed,Object? discount = freezed,Object? tax = freezed,Object? grandTotal = freezed,Object? items = null,}) {
  return _then(_BillDto(
id: freezed == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int?,code: freezed == code ? _self.code : code // ignore: cast_nullable_to_non_nullable
as String?,customerName: freezed == customerName ? _self.customerName : customerName // ignore: cast_nullable_to_non_nullable
as String?,customerPhone: freezed == customerPhone ? _self.customerPhone : customerPhone // ignore: cast_nullable_to_non_nullable
as String?,total: freezed == total ? _self.total : total // ignore: cast_nullable_to_non_nullable
as double?,discount: freezed == discount ? _self.discount : discount // ignore: cast_nullable_to_non_nullable
as double?,tax: freezed == tax ? _self.tax : tax // ignore: cast_nullable_to_non_nullable
as double?,grandTotal: freezed == grandTotal ? _self.grandTotal : grandTotal // ignore: cast_nullable_to_non_nullable
as double?,items: null == items ? _self._items : items // ignore: cast_nullable_to_non_nullable
as List<SaleItemDto>,
  ));
}


}

// dart format on
