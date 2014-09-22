<div style="font-family: Arial; border: 1px solid #CCC; width: 50%;">
	<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
		<tr style="height: 50px; background-color:#25AAE1; text-align: center; vertical-align: middle;">
			<td>
				<span style="font-size: 23px; color: #FFFFFF;">Add New Attribute</span>
			</td>
		</tr>
		<tr>
			<table style="background-color: #ECEDEE; width: 100%; text-align: center;" border="0" cellpadding="0" cellspacing="0">
				<tr style="height: 50px;">
					<td colspan="4">
						<input type="text" id="txtAttName" name="txtAttName" placeholder="Name" style="width: 320px; text-indent: 5px; height: 30px;" />
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="4">
					</td>
				</tr>
				<tr style="height: 50px;">
					<td colspan="4">
						<input type="text" id="txtAttTitle" name="txtAttTitle" placeholder="Display Title (Example - &quot;Choose Sause&quot;)" style="width: 320px; text-indent: 5px; height: 30px;" />
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="4">
					</td>
				</tr>
				<tr>
					<td style="width: 10%;">
					</td>
					<td align="left">
						<input type="checkbox" name="chkAttReq" id="chkAttReq"/><span style="color: #25AAE1; font-size:14px;">Attribute Required for Ordering</span>
					</td>
					<td style="width: 10%;">
					</td>
					<td align="left">
						<input type="checkbox" name="chkAttAdd" id="chkAttAdd"/><span style="color: #25AAE1; font-size:14px;">Attribute adds to price</span><br />
						<input type="checkbox" name="chkAttTotal" id="chkAttTotal" /><span style="color: #25AAE1; font-size:14px;">Attribute displays total price</span>
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="4">
					</td>
				</tr>
				<tr>
					<td align="center" colspan="4" align="center">
						<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td style="width: 30%;">
								</td>
								<td style="width: 10%;" valign="top">
									<span style="color: #25AAE1; font-size:14px;">Layout:</span>
								</td>
								<td>
									<input type="radio" name="rbAtt" id="rbAttDD" value="1" /><span style="font-size:14px;">Drop Down Menu</span><br />
									<input type="radio" name="rbAtt" id="rbCB" value="2" /><span style="font-size:14px;">Check Boxes</span><br />
									<input type="radio" name="rbAtt" id="rbRB" value="3" /><span style="font-size:14px;">Radio Buttons</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="4">
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td style="width: 5%;">
								</td>
								<td style="width: 90%;">
									<hr />
								</td>
								<td style="width: 5%;">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="4">
					</td>
				</tr>
				<tr>
					<td style="width: 10%;">
					</td>
					<td align="left">
						<input type="text" id="txtAttSubTitle" name="txtAttSubTitle" placeholder="Example - &quot;Hot Sause&quot;" style="width: 250px; text-indent: 5px; height: 30px;" />
					</td>
					<td style="width: 10%;">
					</td>
					<td align="left">
						<input type="text" id="txtAttPrice" name="txtAttPrice" placeholder="Price (&quot;-$.75&quot;)" style="width: 120px; text-indent: 5px; height: 30px;" />
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="4">
					</td>
				</tr>
				<tr>
					<td style="width: 10%;">
					</td>
					<td colspan="3" align="left">
						<input type="checkbox" name="chkAttReq" id="chkAttReq"/><span style="color: #25AAE1; font-size:14px;">Default Attribute?</span>
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="4">
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<input type="button" id="btnAdd" name="btnAdd" value="Add" style="width: 100px; border: 1px solid #25AAE1; color: #FFFFFF; background-color: #25AAE1; display: inline-block; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;">
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="4">
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td style="width: 5%;">
								</td>
								<td style="width: 90%;">
									<table style=" font-size: 12px; width: 100%; border: 1px solid #CCCCCC; background-color: #FFFFFF;" cellpadding="0" cellspacing="0">
										<tr style="height: 5px;">
											<td colspan="6">
											</td>
										</tr>
										<tr>
											<td style="width: 5%;">
											</td>
											<td style="width: 50%;">
												Selection
											</td>
											<td style="width: 15%;">
												Price
											</td>
											<td style="width: 15%;">
												Default
											</td>
											<td style="width: 10%;">
												<img src="img/delete.png" alt="Delete" title="Delete" name="imgAttDel" id="imgAttDel"/>
											</td>
											<td style="width: 5%;">
											</td>
										</tr>
										<tr style="height: 5px;">
											<td colspan="6">
											</td>
										</tr>	
										<tr>
											<td style="width: 5%;">
											</td>
											<td style="width: 50%;">
												Selection
											</td>
											<td style="width: 15%;">
												Price
											</td>
											<td style="width: 15%;">
												Default
											</td>
											<td style="width: 10%;">
												<img src="img/delete.png" alt="Delete" title="Delete" name="imgAttDel" id="imgAttDel"/>
											</td>
											<td style="width: 5%;">
											</td>
										</tr>
										<tr style="height: 5px;">
											<td colspan="6">
											</td>
										</tr>	
										<tr>
											<td style="width: 5%;">
											</td>
											<td style="width: 50%;">
												Selection
											</td>
											<td style="width: 15%;">
												Price
											</td>
											<td style="width: 15%;">
												Default
											</td>
											<td style="width: 10%;">
												<img src="img/delete.png" alt="Delete" title="Delete" name="imgAttDel" id="imgAttDel"/>
											</td>
											<td style="width: 5%;">
											</td>
										</tr>
										<tr style="height: 5px;">
											<td colspan="6">
											</td>
										</tr>	
										<tr>
											<td style="width: 5%;">
											</td>
											<td style="width: 50%;">
												Selection
											</td>
											<td style="width: 15%;">
												Price
											</td>
											<td style="width: 15%;">
												Default
											</td>
											<td style="width: 10%;">
												<img src="img/delete.png" alt="Delete" title="Delete" name="imgAttDel" id="imgAttDel"/>
											</td>
											<td style="width: 5%;">
											</td>
										</tr>
										<tr style="height: 5px;">
											<td colspan="6">
											</td>
										</tr>	
									</table>
								</td>
								<td style="width: 5%;">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="4">
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td style="width: 5%;"></td>
								<td style="width: 65%;">
									<span style="font-size: 14px;">
										Would you like to apply this to an entite Submenu?
									</span>
									<input type="checkbox" name="chkAttEntire" id="chkAttEntire"/>
								</td>
								<td style="width: 25%;" align="right">
									<input type="button" id="btnSave" name="btnSave" value="Save" style="width: 120px; height: 30px; border: 1px solid #25AAE1; color: #FFFFFF; background-color: #25AAE1; display: inline-block; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;">
								</td>
								<td style="width: 5%;">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="4">
					</td>
				</tr>
			</table>
		</tr>
	</table>
</div>