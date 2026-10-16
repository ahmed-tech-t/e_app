// GENERATED CODE - DO NOT MODIFY BY HAND
// coverage:ignore-file
// ignore_for_file: type=lint, type=warning, deprecated_member_use, deprecated_member_use_from_same_package
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'sale_item_dto.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

// GENERATED CODE - DO NOT MODIFY BY HAND
// dart format off
T _$identity<T>(T value) => value;

/// @nodoc
mixin _$SaleItemDto {

 int? get id;@JsonKey(name: 'product_id') int? get productId;@JsonKey(name: 'bill_id') int? get billId; int? get quantity; double? get price; double? get total;
/// Create a copy of SaleItemDto
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$SaleItemDtoCopyWith<SaleItemDto> get copyWith => _$SaleItemDtoCopyWithImpl<SaleItemDto>(this as SaleItemDto, _$identity);

  /// Serializes this SaleItemDto to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as SaleItemDto;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is SaleItemDto&&(identical(other.id, _this.id) || other.id == _this.id)&&(identical(other.productId, _this.productId) || other.productId == _this.productId)&&(identical(other.billId, _this.billId) || other.billId == _this.billId)&&(identical(other.quantity, _this.quantity) || other.quantity == _this.quantity)&&(identical(other.price, _this.price) || other.price == _this.price)&&(identical(other.total, _this.total) || other.total == _this.total));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as SaleItemDto;
  return Object.hash(runtimeType,_this.id,_this.productId,_this.billId,_this.quantity,_this.price,_this.total);
}

@override
String toString() {
  final _this = this as SaleItemDto;
  return 'SaleItemDto(id: ${_this.id}, productId: ${_this.productId}, billId: ${_this.billId}, quantity: ${_this.quantity}, price: ${_this.price}, total: ${_this.total})';
}


}

/// @nodoc
abstract mixin class $SaleItemDtoCopyWith<$Res>  {
  factory $SaleItemDtoCopyWith(SaleItemDto value, $Res Function(SaleItemDto) _then) = _$SaleItemDtoCopyWithImpl;
@useResult
$Res call({
 int? id,@JsonKey(name: 'product_id') int? productId,@JsonKey(name: 'bill_id') int? billId, int? quantity, double? price, double? total
});




}
/// @nodoc
class _$SaleItemDtoCopyWithImpl<$Res>
    implements $SaleItemDtoCopyWith<$Res> {
  _$SaleItemDtoCopyWithImpl(this._self, this._then);

  final SaleItemDto _self;
  final $Res Function(SaleItemDto) _then;

/// Create a copy of SaleItemDto
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? id = freezed,Object? productId = freezed,Object? billId = freezed,Object? quantity = freezed,Object? price = freezed,Object? total = freezed,}) {
  return _then(SaleItemDto(
id: freezed == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int?,productId: freezed == productId ? _self.productId : productId // ignore: cast_nullable_to_non_nullable
as int?,billId: freezed == billId ? _self.billId : billId // ignore: cast_nullable_to_non_nullable
as int?,quantity: freezed == quantity ? _self.quantity : quantity // ignore: cast_nullable_to_non_nullable
as int?,price: freezed == price ? _self.price : price // ignore: cast_nullable_to_non_nullable
as double?,total: freezed == total ? _self.total : total // ignore: cast_nullable_to_non_nullable
as double?,
  ));
}

}


/// Adds pattern-matching-related methods to [SaleItemDto].
extension SaleItemDtoPatterns on SaleItemDto {
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

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _SaleItemDto value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _SaleItemDto() when $default != null:
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

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _SaleItemDto value)  $default,){
final _that = this;
switch (_that) {
case _SaleItemDto():
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

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _SaleItemDto value)?  $default,){
final _that = this;
switch (_that) {
case _SaleItemDto() when $default != null:
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

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( int? id, @JsonKey(name: 'product_id')  int? productId, @JsonKey(name: 'bill_id')  int? billId,  int? quantity,  double? price,  double? total)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _SaleItemDto() when $default != null:
return $default(_that.id,_that.productId,_that.billId,_that.quantity,_that.price,_that.total);case _:
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

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( int? id, @JsonKey(name: 'product_id')  int? productId, @JsonKey(name: 'bill_id')  int? billId,  int? quantity,  double? price,  double? total)  $default,) {final _that = this;
switch (_that) {
case _SaleItemDto():
return $default(_that.id,_that.productId,_that.billId,_that.quantity,_that.price,_that.total);case _:
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

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( int? id, @JsonKey(name: 'product_id')  int? productId, @JsonKey(name: 'bill_id')  int? billId,  int? quantity,  double? price,  double? total)?  $default,) {final _that = this;
switch (_that) {
case _SaleItemDto() when $default != null:
return $default(_that.id,_that.productId,_that.billId,_that.quantity,_that.price,_that.total);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _SaleItemDto implements SaleItemDto {
  const _SaleItemDto({this.id, @JsonKey(name: 'product_id') this.productId, @JsonKey(name: 'bill_id') this.billId, this.quantity, this.price, this.total});
  factory _SaleItemDto.fromJson(Map<String, dynamic> json) => _$SaleItemDtoFromJson(json);

@override final  int? id;
@override@JsonKey(name: 'product_id') final  int? productId;
@override@JsonKey(name: 'bill_id') final  int? billId;
@override final  int? quantity;
@override final  double? price;
@override final  double? total;

/// Create a copy of SaleItemDto
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$SaleItemDtoCopyWith<_SaleItemDto> get copyWith => __$SaleItemDtoCopyWithImpl<_SaleItemDto>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$SaleItemDtoToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _SaleItemDto&&(identical(other.id, id) || other.id == id)&&(identical(other.productId, productId) || other.productId == productId)&&(identical(other.billId, billId) || other.billId == billId)&&(identical(other.quantity, quantity) || other.quantity == quantity)&&(identical(other.price, price) || other.price == price)&&(identical(other.total, total) || other.total == total));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hash(runtimeType,id,productId,billId,quantity,price,total);
}

@override
String toString() {
    return 'SaleItemDto(id: $id, productId: $productId, billId: $billId, quantity: $quantity, price: $price, total: $total)';
}


}

/// @nodoc
abstract mixin class _$SaleItemDtoCopyWith<$Res> implements $SaleItemDtoCopyWith<$Res> {
  factory _$SaleItemDtoCopyWith(_SaleItemDto value, $Res Function(_SaleItemDto) _then) = __$SaleItemDtoCopyWithImpl;
@override @useResult
$Res call({
 int? id,@JsonKey(name: 'product_id') int? productId,@JsonKey(name: 'bill_id') int? billId, int? quantity, double? price, double? total
});




}
/// @nodoc
class __$SaleItemDtoCopyWithImpl<$Res>
    implements _$SaleItemDtoCopyWith<$Res> {
  __$SaleItemDtoCopyWithImpl(this._self, this._then);

  final _SaleItemDto _self;
  final $Res Function(_SaleItemDto) _then;

/// Create a copy of SaleItemDto
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? id = freezed,Object? productId = freezed,Object? billId = freezed,Object? quantity = freezed,Object? price = freezed,Object? total = freezed,}) {
  return _then(_SaleItemDto(
id: freezed == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int?,productId: freezed == productId ? _self.productId : productId // ignore: cast_nullable_to_non_nullable
as int?,billId: freezed == billId ? _self.billId : billId // ignore: cast_nullable_to_non_nullable
as int?,quantity: freezed == quantity ? _self.quantity : quantity // ignore: cast_nullable_to_non_nullable
as int?,price: freezed == price ? _self.price : price // ignore: cast_nullable_to_non_nullable
as double?,total: freezed == total ? _self.total : total // ignore: cast_nullable_to_non_nullable
as double?,
  ));
}


}

// dart format on
