// GENERATED CODE - DO NOT MODIFY BY HAND
// coverage:ignore-file
// ignore_for_file: type=lint, type=warning, deprecated_member_use, deprecated_member_use_from_same_package
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'Product.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

// GENERATED CODE - DO NOT MODIFY BY HAND
// dart format off
T _$identity<T>(T value) => value;
/// @nodoc
mixin _$Product {

 int? get id; String? get originalCode; String? get nameAr; String? get nameEn; String? get code; String? get description; String? get brand; String? get image; double? get retailprice; double? get wholeprice; num? get unitsPerCarton;
/// Create a copy of Product
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$ProductCopyWith<Product> get copyWith => _$ProductCopyWithImpl<Product>(this as Product, _$identity);



@override
bool operator ==(Object other) {
  final _this = this as Product;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is Product&&(identical(other.id, _this.id) || other.id == _this.id)&&(identical(other.originalCode, _this.originalCode) || other.originalCode == _this.originalCode)&&(identical(other.nameAr, _this.nameAr) || other.nameAr == _this.nameAr)&&(identical(other.nameEn, _this.nameEn) || other.nameEn == _this.nameEn)&&(identical(other.code, _this.code) || other.code == _this.code)&&(identical(other.description, _this.description) || other.description == _this.description)&&(identical(other.brand, _this.brand) || other.brand == _this.brand)&&(identical(other.image, _this.image) || other.image == _this.image)&&(identical(other.retailprice, _this.retailprice) || other.retailprice == _this.retailprice)&&(identical(other.wholeprice, _this.wholeprice) || other.wholeprice == _this.wholeprice)&&(identical(other.unitsPerCarton, _this.unitsPerCarton) || other.unitsPerCarton == _this.unitsPerCarton));
}


@override
int get hashCode {
  final _this = this as Product;
  return Object.hash(runtimeType,_this.id,_this.originalCode,_this.nameAr,_this.nameEn,_this.code,_this.description,_this.brand,_this.image,_this.retailprice,_this.wholeprice,_this.unitsPerCarton);
}

@override
String toString() {
  final _this = this as Product;
  return 'Product(id: ${_this.id}, originalCode: ${_this.originalCode}, nameAr: ${_this.nameAr}, nameEn: ${_this.nameEn}, code: ${_this.code}, description: ${_this.description}, brand: ${_this.brand}, image: ${_this.image}, retailprice: ${_this.retailprice}, wholeprice: ${_this.wholeprice}, unitsPerCarton: ${_this.unitsPerCarton})';
}


}

/// @nodoc
abstract mixin class $ProductCopyWith<$Res>  {
  factory $ProductCopyWith(Product value, $Res Function(Product) _then) = _$ProductCopyWithImpl;
@useResult
$Res call({
 int? id, String? originalCode, String? nameAr, String? nameEn, String? code, String? description, String? brand, String? image, double? retailprice, double? wholeprice, num? unitsPerCarton
});




}
/// @nodoc
class _$ProductCopyWithImpl<$Res>
    implements $ProductCopyWith<$Res> {
  _$ProductCopyWithImpl(this._self, this._then);

  final Product _self;
  final $Res Function(Product) _then;

/// Create a copy of Product
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? id = freezed,Object? originalCode = freezed,Object? nameAr = freezed,Object? nameEn = freezed,Object? code = freezed,Object? description = freezed,Object? brand = freezed,Object? image = freezed,Object? retailprice = freezed,Object? wholeprice = freezed,Object? unitsPerCarton = freezed,}) {
  return _then(Product(
id: freezed == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int?,originalCode: freezed == originalCode ? _self.originalCode : originalCode // ignore: cast_nullable_to_non_nullable
as String?,nameAr: freezed == nameAr ? _self.nameAr : nameAr // ignore: cast_nullable_to_non_nullable
as String?,nameEn: freezed == nameEn ? _self.nameEn : nameEn // ignore: cast_nullable_to_non_nullable
as String?,code: freezed == code ? _self.code : code // ignore: cast_nullable_to_non_nullable
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


/// Adds pattern-matching-related methods to [Product].
extension ProductPatterns on Product {
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

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _Product value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _Product() when $default != null:
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

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _Product value)  $default,){
final _that = this;
switch (_that) {
case _Product():
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

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _Product value)?  $default,){
final _that = this;
switch (_that) {
case _Product() when $default != null:
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

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( int? id,  String? originalCode,  String? nameAr,  String? nameEn,  String? code,  String? description,  String? brand,  String? image,  double? retailprice,  double? wholeprice,  num? unitsPerCarton)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _Product() when $default != null:
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

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( int? id,  String? originalCode,  String? nameAr,  String? nameEn,  String? code,  String? description,  String? brand,  String? image,  double? retailprice,  double? wholeprice,  num? unitsPerCarton)  $default,) {final _that = this;
switch (_that) {
case _Product():
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

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( int? id,  String? originalCode,  String? nameAr,  String? nameEn,  String? code,  String? description,  String? brand,  String? image,  double? retailprice,  double? wholeprice,  num? unitsPerCarton)?  $default,) {final _that = this;
switch (_that) {
case _Product() when $default != null:
return $default(_that.id,_that.originalCode,_that.nameAr,_that.nameEn,_that.code,_that.description,_that.brand,_that.image,_that.retailprice,_that.wholeprice,_that.unitsPerCarton);case _:
  return null;

}
}

}

/// @nodoc


class _Product implements Product {
  const _Product({this.id, this.originalCode, this.nameAr, this.nameEn, this.code, this.description, this.brand, this.image, this.retailprice, this.wholeprice, this.unitsPerCarton});
  

@override final  int? id;
@override final  String? originalCode;
@override final  String? nameAr;
@override final  String? nameEn;
@override final  String? code;
@override final  String? description;
@override final  String? brand;
@override final  String? image;
@override final  double? retailprice;
@override final  double? wholeprice;
@override final  num? unitsPerCarton;

/// Create a copy of Product
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$ProductCopyWith<_Product> get copyWith => __$ProductCopyWithImpl<_Product>(this, _$identity);



@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _Product&&(identical(other.id, id) || other.id == id)&&(identical(other.originalCode, originalCode) || other.originalCode == originalCode)&&(identical(other.nameAr, nameAr) || other.nameAr == nameAr)&&(identical(other.nameEn, nameEn) || other.nameEn == nameEn)&&(identical(other.code, code) || other.code == code)&&(identical(other.description, description) || other.description == description)&&(identical(other.brand, brand) || other.brand == brand)&&(identical(other.image, image) || other.image == image)&&(identical(other.retailprice, retailprice) || other.retailprice == retailprice)&&(identical(other.wholeprice, wholeprice) || other.wholeprice == wholeprice)&&(identical(other.unitsPerCarton, unitsPerCarton) || other.unitsPerCarton == unitsPerCarton));
}


@override
int get hashCode {
    return Object.hash(runtimeType,id,originalCode,nameAr,nameEn,code,description,brand,image,retailprice,wholeprice,unitsPerCarton);
}

@override
String toString() {
    return 'Product(id: $id, originalCode: $originalCode, nameAr: $nameAr, nameEn: $nameEn, code: $code, description: $description, brand: $brand, image: $image, retailprice: $retailprice, wholeprice: $wholeprice, unitsPerCarton: $unitsPerCarton)';
}


}

/// @nodoc
abstract mixin class _$ProductCopyWith<$Res> implements $ProductCopyWith<$Res> {
  factory _$ProductCopyWith(_Product value, $Res Function(_Product) _then) = __$ProductCopyWithImpl;
@override @useResult
$Res call({
 int? id, String? originalCode, String? nameAr, String? nameEn, String? code, String? description, String? brand, String? image, double? retailprice, double? wholeprice, num? unitsPerCarton
});




}
/// @nodoc
class __$ProductCopyWithImpl<$Res>
    implements _$ProductCopyWith<$Res> {
  __$ProductCopyWithImpl(this._self, this._then);

  final _Product _self;
  final $Res Function(_Product) _then;

/// Create a copy of Product
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? id = freezed,Object? originalCode = freezed,Object? nameAr = freezed,Object? nameEn = freezed,Object? code = freezed,Object? description = freezed,Object? brand = freezed,Object? image = freezed,Object? retailprice = freezed,Object? wholeprice = freezed,Object? unitsPerCarton = freezed,}) {
  return _then(_Product(
id: freezed == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int?,originalCode: freezed == originalCode ? _self.originalCode : originalCode // ignore: cast_nullable_to_non_nullable
as String?,nameAr: freezed == nameAr ? _self.nameAr : nameAr // ignore: cast_nullable_to_non_nullable
as String?,nameEn: freezed == nameEn ? _self.nameEn : nameEn // ignore: cast_nullable_to_non_nullable
as String?,code: freezed == code ? _self.code : code // ignore: cast_nullable_to_non_nullable
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
