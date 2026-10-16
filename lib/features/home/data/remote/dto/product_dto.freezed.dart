// GENERATED CODE - DO NOT MODIFY BY HAND
// coverage:ignore-file
// ignore_for_file: type=lint, type=warning, deprecated_member_use, deprecated_member_use_from_same_package
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'product_dto.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

// GENERATED CODE - DO NOT MODIFY BY HAND
// dart format off
T _$identity<T>(T value) => value;

/// @nodoc
mixin _$ProductDto {

 int? get id;@JsonKey(name: 'original_code') String? get originalCode;@JsonKey(name: 'name_ar') String get nameAr;@JsonKey(name: 'name_en') String get nameEn; String? get code; String? get description; String? get brand; String? get image;@JsonKey(name: 'retail_price') double? get retailprice;@JsonKey(name: 'wholesale_price') double? get wholeprice;@JsonKey(name: 'units_per_carton') num? get unitsPerCarton;
/// Create a copy of ProductDto
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$ProductDtoCopyWith<ProductDto> get copyWith => _$ProductDtoCopyWithImpl<ProductDto>(this as ProductDto, _$identity);

  /// Serializes this ProductDto to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as ProductDto;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is ProductDto&&(identical(other.id, _this.id) || other.id == _this.id)&&(identical(other.originalCode, _this.originalCode) || other.originalCode == _this.originalCode)&&(identical(other.nameAr, _this.nameAr) || other.nameAr == _this.nameAr)&&(identical(other.nameEn, _this.nameEn) || other.nameEn == _this.nameEn)&&(identical(other.code, _this.code) || other.code == _this.code)&&(identical(other.description, _this.description) || other.description == _this.description)&&(identical(other.brand, _this.brand) || other.brand == _this.brand)&&(identical(other.image, _this.image) || other.image == _this.image)&&(identical(other.retailprice, _this.retailprice) || other.retailprice == _this.retailprice)&&(identical(other.wholeprice, _this.wholeprice) || other.wholeprice == _this.wholeprice)&&(identical(other.unitsPerCarton, _this.unitsPerCarton) || other.unitsPerCarton == _this.unitsPerCarton));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as ProductDto;
  return Object.hash(runtimeType,_this.id,_this.originalCode,_this.nameAr,_this.nameEn,_this.code,_this.description,_this.brand,_this.image,_this.retailprice,_this.wholeprice,_this.unitsPerCarton);
}

@override
String toString() {
  final _this = this as ProductDto;
  return 'ProductDto(id: ${_this.id}, originalCode: ${_this.originalCode}, nameAr: ${_this.nameAr}, nameEn: ${_this.nameEn}, code: ${_this.code}, description: ${_this.description}, brand: ${_this.brand}, image: ${_this.image}, retailprice: ${_this.retailprice}, wholeprice: ${_this.wholeprice}, unitsPerCarton: ${_this.unitsPerCarton})';
}


}

/// @nodoc
abstract mixin class $ProductDtoCopyWith<$Res>  {
  factory $ProductDtoCopyWith(ProductDto value, $Res Function(ProductDto) _then) = _$ProductDtoCopyWithImpl;
@useResult
$Res call({
 int? id,@JsonKey(name: 'original_code') String? originalCode,@JsonKey(name: 'name_ar') String nameAr,@JsonKey(name: 'name_en') String nameEn, String? code, String? description, String? brand, String? image,@JsonKey(name: 'retail_price') double? retailprice,@JsonKey(name: 'wholesale_price') double? wholeprice,@JsonKey(name: 'units_per_carton') num? unitsPerCarton
});




}
/// @nodoc
class _$ProductDtoCopyWithImpl<$Res>
    implements $ProductDtoCopyWith<$Res> {
  _$ProductDtoCopyWithImpl(this._self, this._then);

  final ProductDto _self;
  final $Res Function(ProductDto) _then;

/// Create a copy of ProductDto
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? id = freezed,Object? originalCode = freezed,Object? nameAr = null,Object? nameEn = null,Object? code = freezed,Object? description = freezed,Object? brand = freezed,Object? image = freezed,Object? retailprice = freezed,Object? wholeprice = freezed,Object? unitsPerCarton = freezed,}) {
  return _then(ProductDto(
id: freezed == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int?,originalCode: freezed == originalCode ? _self.originalCode : originalCode // ignore: cast_nullable_to_non_nullable
as String?,nameAr: null == nameAr ? _self.nameAr : nameAr // ignore: cast_nullable_to_non_nullable
as String,nameEn: null == nameEn ? _self.nameEn : nameEn // ignore: cast_nullable_to_non_nullable
as String,code: freezed == code ? _self.code : code // ignore: cast_nullable_to_non_nullable
as String?,description: freezed == description ? _self.description : description // ignore: cast_nullable_to_non_nullable
as String?,brand: freezed == brand ? _self.brand : brand // ignore: cast_nullable_to_non_nullable
as String?,image: freezed == image ? _self.image : image // ignore: cast_nullable_to_non_nullable
as String?,retailprice: freezed == retailprice ? _self.retailprice : retailprice // ignore: cast_nullable_to_non_nullable
as double?,wholeprice: freezed == wholeprice ? _self.wholeprice : wholeprice // ignore: cast_nullable_to_non_nullable
as double?,unitsPerCarton: freezed == unitsPerCarton ? _self.unitsPerCarton : unitsPerCarton // ignore: cast_nullable_to_non_nullable
as num?,
  ));
}

}


/// Adds pattern-matching-related methods to [ProductDto].
extension ProductDtoPatterns on ProductDto {
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

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _ProductDto value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _ProductDto() when $default != null:
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

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _ProductDto value)  $default,){
final _that = this;
switch (_that) {
case _ProductDto():
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

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _ProductDto value)?  $default,){
final _that = this;
switch (_that) {
case _ProductDto() when $default != null:
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

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( int? id, @JsonKey(name: 'original_code')  String? originalCode, @JsonKey(name: 'name_ar')  String nameAr, @JsonKey(name: 'name_en')  String nameEn,  String? code,  String? description,  String? brand,  String? image, @JsonKey(name: 'retail_price')  double? retailprice, @JsonKey(name: 'wholesale_price')  double? wholeprice, @JsonKey(name: 'units_per_carton')  num? unitsPerCarton)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _ProductDto() when $default != null:
return $default(_that.id,_that.originalCode,_that.nameAr,_that.nameEn,_that.code,_that.description,_that.brand,_that.image,_that.retailprice,_that.wholeprice,_that.unitsPerCarton);case _:
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

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( int? id, @JsonKey(name: 'original_code')  String? originalCode, @JsonKey(name: 'name_ar')  String nameAr, @JsonKey(name: 'name_en')  String nameEn,  String? code,  String? description,  String? brand,  String? image, @JsonKey(name: 'retail_price')  double? retailprice, @JsonKey(name: 'wholesale_price')  double? wholeprice, @JsonKey(name: 'units_per_carton')  num? unitsPerCarton)  $default,) {final _that = this;
switch (_that) {
case _ProductDto():
return $default(_that.id,_that.originalCode,_that.nameAr,_that.nameEn,_that.code,_that.description,_that.brand,_that.image,_that.retailprice,_that.wholeprice,_that.unitsPerCarton);case _:
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

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( int? id, @JsonKey(name: 'original_code')  String? originalCode, @JsonKey(name: 'name_ar')  String nameAr, @JsonKey(name: 'name_en')  String nameEn,  String? code,  String? description,  String? brand,  String? image, @JsonKey(name: 'retail_price')  double? retailprice, @JsonKey(name: 'wholesale_price')  double? wholeprice, @JsonKey(name: 'units_per_carton')  num? unitsPerCarton)?  $default,) {final _that = this;
switch (_that) {
case _ProductDto() when $default != null:
return $default(_that.id,_that.originalCode,_that.nameAr,_that.nameEn,_that.code,_that.description,_that.brand,_that.image,_that.retailprice,_that.wholeprice,_that.unitsPerCarton);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _ProductDto implements ProductDto {
  const _ProductDto({this.id, @JsonKey(name: 'original_code') this.originalCode, @JsonKey(name: 'name_ar') this.nameAr = '', @JsonKey(name: 'name_en') this.nameEn = '', this.code, this.description, this.brand, this.image, @JsonKey(name: 'retail_price') this.retailprice, @JsonKey(name: 'wholesale_price') this.wholeprice, @JsonKey(name: 'units_per_carton') this.unitsPerCarton});
  factory _ProductDto.fromJson(Map<String, dynamic> json) => _$ProductDtoFromJson(json);

@override final  int? id;
@override@JsonKey(name: 'original_code') final  String? originalCode;
@override@JsonKey(name: 'name_ar') final  String nameAr;
@override@JsonKey(name: 'name_en') final  String nameEn;
@override final  String? code;
@override final  String? description;
@override final  String? brand;
@override final  String? image;
@override@JsonKey(name: 'retail_price') final  double? retailprice;
@override@JsonKey(name: 'wholesale_price') final  double? wholeprice;
@override@JsonKey(name: 'units_per_carton') final  num? unitsPerCarton;

/// Create a copy of ProductDto
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$ProductDtoCopyWith<_ProductDto> get copyWith => __$ProductDtoCopyWithImpl<_ProductDto>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$ProductDtoToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _ProductDto&&(identical(other.id, id) || other.id == id)&&(identical(other.originalCode, originalCode) || other.originalCode == originalCode)&&(identical(other.nameAr, nameAr) || other.nameAr == nameAr)&&(identical(other.nameEn, nameEn) || other.nameEn == nameEn)&&(identical(other.code, code) || other.code == code)&&(identical(other.description, description) || other.description == description)&&(identical(other.brand, brand) || other.brand == brand)&&(identical(other.image, image) || other.image == image)&&(identical(other.retailprice, retailprice) || other.retailprice == retailprice)&&(identical(other.wholeprice, wholeprice) || other.wholeprice == wholeprice)&&(identical(other.unitsPerCarton, unitsPerCarton) || other.unitsPerCarton == unitsPerCarton));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hash(runtimeType,id,originalCode,nameAr,nameEn,code,description,brand,image,retailprice,wholeprice,unitsPerCarton);
}

@override
String toString() {
    return 'ProductDto(id: $id, originalCode: $originalCode, nameAr: $nameAr, nameEn: $nameEn, code: $code, description: $description, brand: $brand, image: $image, retailprice: $retailprice, wholeprice: $wholeprice, unitsPerCarton: $unitsPerCarton)';
}


}

/// @nodoc
abstract mixin class _$ProductDtoCopyWith<$Res> implements $ProductDtoCopyWith<$Res> {
  factory _$ProductDtoCopyWith(_ProductDto value, $Res Function(_ProductDto) _then) = __$ProductDtoCopyWithImpl;
@override @useResult
$Res call({
 int? id,@JsonKey(name: 'original_code') String? originalCode,@JsonKey(name: 'name_ar') String nameAr,@JsonKey(name: 'name_en') String nameEn, String? code, String? description, String? brand, String? image,@JsonKey(name: 'retail_price') double? retailprice,@JsonKey(name: 'wholesale_price') double? wholeprice,@JsonKey(name: 'units_per_carton') num? unitsPerCarton
});




}
/// @nodoc
class __$ProductDtoCopyWithImpl<$Res>
    implements _$ProductDtoCopyWith<$Res> {
  __$ProductDtoCopyWithImpl(this._self, this._then);

  final _ProductDto _self;
  final $Res Function(_ProductDto) _then;

/// Create a copy of ProductDto
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? id = freezed,Object? originalCode = freezed,Object? nameAr = null,Object? nameEn = null,Object? code = freezed,Object? description = freezed,Object? brand = freezed,Object? image = freezed,Object? retailprice = freezed,Object? wholeprice = freezed,Object? unitsPerCarton = freezed,}) {
  return _then(_ProductDto(
id: freezed == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int?,originalCode: freezed == originalCode ? _self.originalCode : originalCode // ignore: cast_nullable_to_non_nullable
as String?,nameAr: null == nameAr ? _self.nameAr : nameAr // ignore: cast_nullable_to_non_nullable
as String,nameEn: null == nameEn ? _self.nameEn : nameEn // ignore: cast_nullable_to_non_nullable
as String,code: freezed == code ? _self.code : code // ignore: cast_nullable_to_non_nullable
as String?,description: freezed == description ? _self.description : description // ignore: cast_nullable_to_non_nullable
as String?,brand: freezed == brand ? _self.brand : brand // ignore: cast_nullable_to_non_nullable
as String?,image: freezed == image ? _self.image : image // ignore: cast_nullable_to_non_nullable
as String?,retailprice: freezed == retailprice ? _self.retailprice : retailprice // ignore: cast_nullable_to_non_nullable
as double?,wholeprice: freezed == wholeprice ? _self.wholeprice : wholeprice // ignore: cast_nullable_to_non_nullable
as double?,unitsPerCarton: freezed == unitsPerCarton ? _self.unitsPerCarton : unitsPerCarton // ignore: cast_nullable_to_non_nullable
as num?,
  ));
}


}

// dart format on
